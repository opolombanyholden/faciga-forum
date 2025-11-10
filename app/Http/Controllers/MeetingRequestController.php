<?php

// app/Http/Controllers/MeetingRequestController.php

namespace App\Http\Controllers;

use App\Models\MeetingRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingRequestController extends Controller
{
    public function store(Request $request)
    {
        $sender = Auth::guard('company')->user();
        
        $validated = $request->validate([
            'receiver_company_id' => 'required|exists:companies,id',
            'meeting_type' => 'required|in:btob,btog,other',
            'message' => 'nullable|string',
        ]);

        $receiver = Company::findOrFail($validated['receiver_company_id']);

        // Vérifier que les entreprises sont de pays différents
        if ($sender->country === $receiver->country) {
            return back()->with('error', 'Les rendez-vous doivent être entre entreprises de pays différents.');
        }

        MeetingRequest::create([
            'sender_company_id' => $sender->id,
            'receiver_company_id' => $validated['receiver_company_id'],
            'meeting_type' => $validated['meeting_type'],
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Demande de rendez-vous envoyée !');
    }

    public function respond(Request $request, $id)
    {
        $meetingRequest = MeetingRequest::findOrFail($id);
        $company = Auth::guard('company')->user();

        if ($meetingRequest->receiver_company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $meetingRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Réponse envoyée !');
    }
}