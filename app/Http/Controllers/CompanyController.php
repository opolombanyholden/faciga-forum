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
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'motivation' => 'nullable|string',
            'sectors_interest' => 'required|array',
            'wants_btob' => 'nullable|boolean',
            'wants_btog' => 'nullable|boolean',
            'other_meetings' => 'nullable|string',
        ]);

        // Gestion de l'upload du logo avec le système Laravel
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Création de l'entreprise avec statut "pending"
        $company = Company::create([
            'name' => $validated['name'],
            'country' => $validated['country'],
            'sector' => $validated['sector'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'logo' => $logoPath,
            'motivation' => $validated['motivation'] ?? null,
            'sectors_interest' => json_encode($validated['sectors_interest'] ?? []), // Conversion en JSON
            'wants_btob' => $request->has('wants_btob'),
            'wants_btog' => $request->has('wants_btog'),
            'other_meetings' => $validated['other_meetings'] ?? null,
            'status' => 'pending', // Statut par défaut
            'confirmed' => false,
        ]);

        // ENVOI UNIQUEMENT DE L'EMAIL DE CONFIRMATION DE RÉCEPTION
        try {
            Mail::to($company->email)->send(new CompanyRegistered($company));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation: ' . $e->getMessage());
        }

        // Redirection vers la page de confirmation avec les données de l'entreprise
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

    public function directory()
    {
        $currentCompany = Auth::guard('company')->user();
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