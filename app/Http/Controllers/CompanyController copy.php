<?php

// app/Http/Controllers/CompanyController.php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyRegistered;
use App\Mail\CompanyApproved;

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
            'wants_btob' => 'boolean',
            'wants_btog' => 'boolean',
            'other_meetings' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $company = Company::create($validated);

        // Envoi email de confirmation
        Mail::to($company->email)->send(new CompanyRegistered($company));

        return redirect()->route('login')->with('success', 'Inscription réussie ! Vous recevrez un email de confirmation.');
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