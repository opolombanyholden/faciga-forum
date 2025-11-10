<?php 
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyApproved;

class AdminController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function dashboard()
    {
        $stats = [
            'pending' => Company::where('status', 'pending')->count(),
            'approved' => Company::where('status', 'approved')->count(),
            'confirmed' => Company::where('confirmed', true)->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function companies()
    {
        $companies = Company::with('participants')->latest()->get();
        return view('admin.companies', compact('companies'));
    }

    public function approve($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['status' => 'approved']);

        Mail::to($company->email)->send(new CompanyApproved($company));

        return back()->with('success', 'Entreprise approuvée !');
    }

    public function reject($id)
    {
        $company = Company::findOrFail($id);
        $company->update(['status' => 'rejected']);

        return back()->with('success', 'Entreprise rejetée.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}