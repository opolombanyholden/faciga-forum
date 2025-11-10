<?php
// app/Http/Controllers/ParticipantController.php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function index()
    {
        $company = Auth::guard('company')->user();
        $participants = $company->participants;
        return view('company.participants.index', compact('participants'));
    }

    public function store(Request $request)
    {
        $company = Auth::guard('company')->user();
        
        if ($company->participants()->count() >= 3) {
            return back()->with('error', 'Maximum 3 participants autorisés.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'function' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $company->participants()->create($validated);

        return back()->with('success', 'Participant ajouté avec succès !');
    }

    public function destroy($id)
    {
        $company = Auth::guard('company')->user();
        $participant = $company->participants()->findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Participant supprimé.');
    }
}