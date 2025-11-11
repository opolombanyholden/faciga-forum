<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyRegistered;

class CompanyController extends Controller
{
    public function create()
    {
        return view('inscription');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|in:Côte d\'Ivoire,Gabon',
            'sector' => 'required|string|max:255',
            'email' => 'required|email|unique:companies',
            // PAS DE PASSWORD ICI - il sera généré lors de l'approbation
            'phone' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'motivation' => 'nullable|string',
            'sectors_interest' => 'required|array',
            'wants_btob' => 'nullable|boolean',
            'wants_btog' => 'nullable|boolean',
            'other_meetings' => 'nullable|string',
            // ✅ VALIDATION DES ACCEPTATIONS
            'accept_terms' => 'required|accepted',
            'accept_data_processing' => 'required|accepted',
        ], [
            // Messages d'erreur personnalisés
            'accept_terms.required' => 'Vous devez accepter les conditions générales de participation.',
            'accept_terms.accepted' => 'Vous devez accepter les conditions générales de participation.',
            'accept_data_processing.required' => 'Vous devez accepter le traitement de vos données.',
            'accept_data_processing.accepted' => 'Vous devez accepter le traitement de vos données.',
        ]);

        // Gestion de l'upload du logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Création de l'entreprise SANS mot de passe (sera généré lors approbation)
        $company = Company::create([
            'name' => $validated['name'],
            'country' => $validated['country'],
            'sector' => $validated['sector'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => null, // NULL - sera généré lors approbation par admin
            'logo' => $logoPath,
            'motivation' => $validated['motivation'] ?? null,
            'sectors_interest' => json_encode($validated['sectors_interest'] ?? []),
            'wants_btob' => $request->has('wants_btob'),
            'wants_btog' => $request->has('wants_btog'),
            'other_meetings' => $validated['other_meetings'] ?? null,
            'status' => 'pending',
            'confirmed' => false,
        ]);

        // ENVOI EMAIL DE CONFIRMATION DE RÉCEPTION UNIQUEMENT
        try {
            Mail::to($company->email)->send(new CompanyRegistered($company));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation: ' . $e->getMessage());
        }

        // Redirection vers page de confirmation
        return redirect()->route('inscription.confirmation')
                        ->with('company', $company);
    }

    public function confirmation()
    {
        $company = session('company');
        
        if (!$company) {
            return redirect()->route('home')->with('error', 'Aucune inscription trouvée.');
        }
        
        return view('inscription.confirmation', compact('company'));
    }

    public function loginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('company')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('company.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('company')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function dashboard()
    {
        $company = Auth::guard('company')->user();
        return view('company.dashboard', compact('company'));
    }

    public function profile()
    {
        $company = Auth::guard('company')->user();
        return view('company.profile', compact('company'));
    }

    public function updateProfile(Request $request)
    {
        $company = Auth::guard('company')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'phone' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'motivation' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function directory()
    {
        $currentCompany = Auth::guard('company')->user();
        
        // Afficher uniquement les entreprises du pays opposé
        $companies = Company::where('status', 'approved')
            ->where('confirmed', true)
            ->where('country', '!=', $currentCompany->country)
            ->get();
        
        return view('company.directory', compact('companies'));
    }

    public function confirmParticipation(Request $request)
    {
        $company = Auth::guard('company')->user();
        
        if ($company->status !== 'approved') {
            return back()->with('error', 'Votre dossier doit être approuvé avant de confirmer.');
        }

        $company->update(['confirmed' => true]);

        return back()->with('success', 'Participation confirmée avec succès !');
    }
}