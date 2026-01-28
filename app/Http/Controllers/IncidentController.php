<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSupport;

class IncidentController extends Controller
{
    // Enregistrer un incident (User)
    public function store(Request $request) {
        $request->validate(['subject' => 'required', 'message' => 'required']);
        
        Incident::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Incident signalé. Nous traitons votre demande.');
    }

    // Marquer comme résolu (Admin/Manager)
    public function resolve($id) {
        $incident = Incident::findOrFail($id);
        $incident->update(['status' => 'resolved']);
        return back()->with('success', 'Incident marqué comme résolu.');
    }

    // Envoyer un email depuis la page d'accueil (Public)
    public function sendContactEmail(Request $request) {
        $request->validate(['email' => 'required|email', 'message' => 'required']);

        // Envoi réel
        try {
            Mail::to(env('ADMIN_EMAIL', 'admin@example.com'))
                ->send(new ContactSupport($request->all()));
            
            return back()->with('success', 'Votre message a bien été envoyé à notre équipe !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }
}