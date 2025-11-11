<?php 

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Participant;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\CompanyApproved;
use App\Mail\CompanyRejected;

class AdminController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function loginForm()
    {
        return view('admin.login');
    }

    /**
     * Authentification admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Log de l'activité de connexion
            $this->logActivity('login', null, null, 'Connexion réussie au dashboard admin');
            
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    /**
     * Dashboard admin avec statistiques
     */
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        // Statistiques complètes
        $stats = [
            'pending' => Company::where('status', 'pending')->count(),
            'approved' => Company::where('status', 'approved')->count(),
            'rejected' => Company::where('status', 'rejected')->count(),
            'confirmed' => Company::where('confirmed', true)->count(),
            'total' => Company::count(),
            'participants_total' => Participant::count(),
            'cote_ivoire' => Company::where('country', 'Côte d\'Ivoire')->count(),
            'gabon' => Company::where('country', 'Gabon')->count(),
            'cote_ivoire_approved' => Company::where('country', 'Côte d\'Ivoire')
                                            ->where('status', 'approved')
                                            ->count(),
            'gabon_approved' => Company::where('country', 'Gabon')
                                      ->where('status', 'approved')
                                      ->count(),
        ];
        
        // Entreprises récentes (10 dernières)
        $recentCompanies = Company::with('participants')
                                  ->latest()
                                  ->take(10)
                                  ->get();
        
        // Statistiques par secteur (Top 5)
        $sectorStats = Company::where('status', 'approved')
                             ->select('sector', DB::raw('COUNT(*) as total'))
                             ->groupBy('sector')
                             ->orderBy('total', 'DESC')
                             ->take(5)
                             ->get();
        
        return view('admin.dashboard', compact('admin', 'stats', 'recentCompanies', 'sectorStats'));
    }

    /**
     * Liste de toutes les entreprises
     */
    public function companies()
    {
        $admin = Auth::guard('admin')->user();
        $companies = Company::with('participants')->latest()->get();
        
        return view('admin.companies', compact('admin', 'companies'));
    }

    /**
     * Afficher les détails d'une entreprise
     */
    public function showCompany($id)
    {
        $admin = Auth::guard('admin')->user();
        $company = Company::with('participants')->findOrFail($id);
        
        $this->logActivity(
            'view_company',
            'Company',
            $company->id,
            "Consultation du dossier de l'entreprise {$company->name}"
        );
        
        return view('admin.company-details', compact('admin', 'company'));
    }

    /**
     * Gestion des participants
     */
    public function participants(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Toutes les entreprises pour le filtre
        $companies = Company::orderBy('name')->get();
        
        // Query de base avec eager loading
        $query = Participant::with('company');
        
        // Filtre par entreprise
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        // Recherche par nom, email ou fonction
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('function', 'LIKE', "%{$search}%");
            });
        }
        
        // Pagination
        $participants = $query->latest()->paginate(20);
        
        return view('admin.participants', compact('admin', 'companies', 'participants'));
    }

    /**
     * ✅ NOUVELLE MÉTHODE : Exporter les entreprises en CSV
     */
    public function exportCompanies()
    {
        $admin = Auth::guard('admin')->user();
        
        // Log de l'activité
        $this->logActivity('export_companies', null, null, 'Export CSV des entreprises');
        
        // Récupérer toutes les entreprises avec participants
        $companies = Company::with('participants')->orderBy('created_at', 'DESC')->get();
        
        // Nom du fichier
        $filename = 'faciga_2025_entreprises_' . date('Y-m-d_His') . '.csv';
        
        // Headers pour le téléchargement
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Callback pour générer le CSV
        $callback = function() use ($companies) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Entreprise',
                'Pays',
                'Secteur',
                'Représentant',
                'Fonction',
                'Email',
                'Téléphone',
                'Site web',
                'Statut',
                'Confirmé',
                'Nb. Participants',
                'Date inscription',
                'Date validation'
            ], ';');
            
            // Données
            foreach ($companies as $company) {
                fputcsv($file, [
                    $company->id,
                    $company->name,
                    $company->country,
                    $company->sector,
                    $company->representative_name,
                    $company->representative_position,
                    $company->email,
                    $company->phone,
                    $company->website ?? '',
                    $this->getStatusLabel($company->status),
                    $company->confirmed ? 'Oui' : 'Non',
                    $company->participants->count(),
                    $company->created_at->format('d/m/Y H:i'),
                    $company->updated_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Approuver une entreprise et générer ses identifiants
     */
    public function approve($id)
    {
        $company = Company::findOrFail($id);
        
        // Générer un mot de passe aléatoire sécurisé (8 caractères)
        $plainPassword = $this->generateSecurePassword();
        
        $oldStatus = $company->status;
        
        // Mettre à jour le statut et le mot de passe
        $company->update([
            'status' => 'approved',
            'password' => Hash::make($plainPassword)
        ]);

        // Log de l'activité
        $this->logActivity(
            'approve_company',
            'Company',
            $company->id,
            "Approbation de l'entreprise {$company->name} et génération des identifiants",
            ['status' => $oldStatus],
            ['status' => 'approved', 'password_generated' => true]
        );

        // Envoi de l'email avec les identifiants
        try {
            Mail::to($company->email)->send(new CompanyApproved($company, $plainPassword));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email approbation: ' . $e->getMessage());
            return back()->with('warning', 'Entreprise approuvée mais erreur lors de l\'envoi de l\'email.');
        }

        return back()->with('success', "Entreprise approuvée ! Email avec identifiants envoyé à {$company->email}");
    }

    /**
     * Rejeter une entreprise
     */
    public function reject(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000'
        ]);
        
        $rejectionReason = $validated['rejection_reason'] ?? 'Votre dossier ne répond pas aux critères de participation.';
        
        $oldStatus = $company->status;
        
        // Mise à jour du statut
        $company->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason
        ]);

        // Log de l'activité
        $this->logActivity(
            'reject_company',
            'Company',
            $company->id,
            "Rejet de l'entreprise {$company->name}",
            ['status' => $oldStatus],
            ['status' => 'rejected', 'rejection_reason' => $rejectionReason]
        );

        // Envoi de l'email de rejet
        try {
            Mail::to($company->email)->send(new CompanyRejected($company, $rejectionReason));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email rejet: ' . $e->getMessage());
        }

        return back()->with('success', 'Entreprise rejetée.');
    }

    /**
     * Déconnexion admin
     */
    public function logout(Request $request)
    {
        $this->logActivity('logout', null, null, 'Déconnexion du dashboard admin');
        
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    /**
     * Générer un mot de passe sécurisé aléatoire
     * Format: 2 majuscules + 4 chiffres + 2 minuscules (8 caractères)
     */
    private function generateSecurePassword()
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        
        $password = '';
        
        // 2 majuscules
        for ($i = 0; $i < 2; $i++) {
            $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        }
        
        // 4 chiffres
        for ($i = 0; $i < 4; $i++) {
            $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        }
        
        // 2 minuscules
        for ($i = 0; $i < 2; $i++) {
            $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        }
        
        // Mélanger les caractères pour plus de sécurité
        return str_shuffle($password);
    }

    /**
     * Obtenir le libellé du statut
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté'
        ];
        
        return $labels[$status] ?? $status;
    }

    /**
     * Logger une activité admin
     */
    private function logActivity(
        string $action, 
        ?string $modelType = null, 
        ?int $modelId = null, 
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        if (!Auth::guard('admin')->check()) {
            return;
        }

        AdminActivityLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }
}