<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification; // <--- AJOUT IMPORTANT
use App\Models\User;

class ReservationController extends Controller
{
    // --- Espace UTILISATEUR ---

    // 1. Afficher le formulaire de réservation
    public function create()
    {
        $resources = Resource::where('status', 'available')->get();
        return view('user.reservations.create', compact('resources'));
    }

    // 2. Enregistrer la demande (Et notifier le Manager)
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string|max:500',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
        ]);

        // --- NOTIFICATION AU MANAGER ---
        $resource = Resource::find($request->resource_id);
        if ($resource && $resource->manager_id) {
            Notification::create([
                'user_id' => $resource->manager_id,
                'message' => "Nouvelle demande de " . Auth::user()->name . " pour " . $resource->label,
                'link' => route('manager.dashboard'),
            ]);
        }
        // -------------------------------

        return redirect()->route('user.dashboard')->with('success', 'Votre demande est enregistrée et en attente de validation.');
    }

    // --- Espace RESPONSABLE ---

    // 3. Traiter une demande (Et notifier l'Utilisateur)
    public function handleRequest(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Sécurité
        if ($reservation->resource->manager_id !== Auth::id()) {
            abort(403, "Action non autorisée sur cette ressource.");
        }

        $messageUser = "";

        if ($request->action === 'approve') {
            $reservation->update(['status' => 'approved']);
            $messageUser = "✅ Votre réservation pour " . $reservation->resource->label . " a été acceptée !";
            
        } elseif ($request->action === 'reject') {
            $reservation->update([
                'status' => 'rejected',
                'manager_feedback' => 'Demande refusée par le responsable.'
            ]);
            $messageUser = "❌ Votre réservation pour " . $reservation->resource->label . " a été refusée.";
        }

        // --- NOTIFICATION A L'UTILISATEUR ---
        if ($messageUser) {
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => $messageUser,
                'link' => route('user.dashboard'),
            ]);
        }
        // ------------------------------------

        return back()->with('success', 'La demande a été traitée.');
    }
}