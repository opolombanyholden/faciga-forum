<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\CompanyApproved;
use App\Mail\CompanyRejected;

class AdminController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function loginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');  // ← IMPORTANT: admin.login (pas juste 'login')
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // IMPORTANT: Utiliser Auth::guard('admin')->attempt()
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Mettre à jour la dernière connexion
            $admin = Auth::guard('admin')->user();
            $admin->update(['last_login_at' => now()]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue ' . $admin->name);
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        // Statistiques générales
        $stats = [
            'total' => Company::count(),
            'pending' => Company::where('status', 'pending')->count(),
            'approved' => Company::where('status', 'approved')->count(),
            'rejected' => Company::where('status', 'rejected')->count(),
            'confirmed' => Company::where('confirmed', true)->count(),
            'participants_total' => Participant::count(),
            'cote_ivoire' => Company::where('country', 'Côte d\'Ivoire')->count(),
            'gabon' => Company::where('country', 'Gabon')->count(),
        ];
        
        // Dernières inscriptions
        $recentCompanies = Company::with('participants')
            ->latest()
            ->take(5)
            ->get();
        
        // Activités récentes (si le log est activé)
        $recentActivities = DB::table('admin_activity_logs')
            ->join('admins', 'admin_activity_logs.admin_id', '=', 'admins.id')
            ->select('admin_activity_logs.*', 'admins.name as admin_name')
            ->orderBy('admin_activity_logs.created_at', 'desc')
            ->take(10)
            ->get();
        
        // Statistiques par secteur
        $sectorStats = Company::select('sector', DB::raw('count(*) as total'))
            ->where('status', 'approved')
            ->groupBy('sector')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact(
            'stats', 
            'recentCompanies', 
            'recentActivities',
            'sectorStats',
            'admin'
        ));
    }

    /**
     * Liste des entreprises avec filtres
     */
    public function companies(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Vérifier les permissions
        if (!$admin->canViewData()) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = Company::with('participants');
        
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }
        
        if ($request->filled('confirmed')) {
            $query->where('confirmed', $request->confirmed === '1');
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sector', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Pagination
        $companies = $query->paginate(20)->withQueryString();
        
        return view('admin.companies', compact('companies', 'admin'));
    }

    /**
     * Détails d'une entreprise
     */
    public function showCompany($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canViewData()) {
            abort(403, 'Accès non autorisé');
        }
        
        $company = Company::with('participants')->findOrFail($id);
        
        return view('admin.company-details', compact('company', 'admin'));
    }

    /**
     * Approuver une entreprise
     */
    public function approve($id, Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canManageCompanies()) {
            return back()->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        $company = Company::findOrFail($id);
        
        if ($company->status === 'approved') {
            return back()->with('info', 'Cette entreprise est déjà approuvée.');
        }
        
        $oldStatus = $company->status;
        $company->update(['status' => 'approved']);
        
        // Enregistrer l'activité
        $this->logActivity(
            'approve_company',
            'Company',
            $company->id,
            "Approbation de l'entreprise {$company->name}",
            ['status' => $oldStatus],
            ['status' => 'approved']
        );
        
        // Envoyer l'email d'approbation
        try {
            Mail::to($company->email)->send(new CompanyApproved($company));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email approbation: ' . $e->getMessage());
        }
        
        return back()->with('success', "L'entreprise {$company->name} a été approuvée avec succès !");
    }

    /**
     * Rejeter une entreprise avec motif
     */
    public function reject($id, Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canManageCompanies()) {
            return back()->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }
        
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000'
        ]);
        
        $company = Company::findOrFail($id);
        
        if ($company->status === 'rejected') {
            return back()->with('info', 'Cette entreprise est déjà rejetée.');
        }
        
        $oldStatus = $company->status;
        $rejectionReason = $request->rejection_reason ?? 'Dossier incomplet ou non conforme';
        
        $company->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason
        ]);
        
        // Enregistrer l'activité
        $this->logActivity(
            'reject_company',
            'Company',
            $company->id,
            "Rejet de l'entreprise {$company->name}",
            ['status' => $oldStatus],
            ['status' => 'rejected', 'reason' => $rejectionReason]
        );
        
        // Envoyer l'email de rejet
        try {
            Mail::to($company->email)->send(new CompanyRejected($company, $rejectionReason));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email rejet: ' . $e->getMessage());
        }
        
        return back()->with('success', "L'entreprise {$company->name} a été rejetée.");
    }

    /**
     * Supprimer une entreprise (Super Admin uniquement)
     */
    public function deleteCompany($id)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin()) {
            return back()->with('error', 'Seul le super administrateur peut supprimer des entreprises.');
        }
        
        $company = Company::findOrFail($id);
        $companyName = $company->name;
        
        // Enregistrer avant suppression
        $this->logActivity(
            'delete_company',
            'Company',
            $company->id,
            "Suppression de l'entreprise {$companyName}"
        );
        
        $company->delete();
        
        return redirect()->route('admin.companies')
            ->with('success', "L'entreprise {$companyName} a été supprimée.");
    }

    /**
     * Gestion des participants
     */
    public function participants(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canViewData()) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = Participant::with('company');
        
        // Filtres
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('function', 'like', "%{$search}%");
            });
        }
        
        $participants = $query->paginate(30)->withQueryString();
        $companies = Company::where('status', 'approved')->orderBy('name')->get();
        
        return view('admin.participants', compact('participants', 'companies', 'admin'));
    }

    /**
     * Export des données en CSV
     */
    public function exportCompanies(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canViewData()) {
            abort(403, 'Accès non autorisé');
        }
        
        $companies = Company::with('participants')->get();
        
        $filename = 'faciga_companies_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($companies) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Nom',
                'Pays',
                'Secteur',
                'Email',
                'Téléphone',
                'Statut',
                'Confirmé',
                'Participants',
                'Date inscription'
            ], ';');
            
            // Données
            foreach ($companies as $company) {
                fputcsv($file, [
                    $company->id,
                    $company->name,
                    $company->country,
                    $company->sector,
                    $company->email,
                    $company->phone,
                    $company->status,
                    $company->confirmed ? 'Oui' : 'Non',
                    $company->participants->count(),
                    $company->created_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };
        
        // Enregistrer l'export
        $this->logActivity(
            'export_companies',
            null,
            null,
            "Export de {$companies->count()} entreprises en CSV"
        );
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Logs d'activité
     */
    public function activityLogs(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->isSuperAdmin() && !$admin->isModerator()) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = DB::table('admin_activity_logs')
            ->join('admins', 'admin_activity_logs.admin_id', '=', 'admins.id')
            ->select('admin_activity_logs.*', 'admins.name as admin_name', 'admins.email as admin_email');
        
        // Filtres
        if ($request->filled('admin_id')) {
            $query->where('admin_activity_logs.admin_id', $request->admin_id);
        }
        
        if ($request->filled('action')) {
            $query->where('admin_activity_logs.action', $request->action);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('admin_activity_logs.created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('admin_activity_logs.created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('admin_activity_logs.created_at', 'desc')
            ->paginate(50)
            ->withQueryString();
        
        $admins = Admin::where('status', 'active')->orderBy('name')->get();
        
        return view('admin.activity-logs', compact('logs', 'admins', 'admin'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $this->logActivity('logout', null, null, 'Déconnexion');
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Méthode privée pour enregistrer les activités
     */
    private function logActivity(
        string $action, 
        ?string $modelType = null, 
        ?int $modelId = null, 
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        try {
            DB::table('admin_activity_logs')->insert([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'description' => $description,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur log activité admin: ' . $e->getMessage());
        }
    }
}