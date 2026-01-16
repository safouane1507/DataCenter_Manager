<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    // Vue pour l'invité (Public)
    public function guestIndex() {
        $resources = Resource::where('status', 'available')->get();
        return view('guest.index', compact('resources'));
    }

    // Détail d'une ressource (Nouveau)
    public function resourceDetail($id) {
        $resource = Resource::findOrFail($id);
        return view('guest.resource_detail', compact('resource'));
    }

    // Formulaire d'inscription (Nouveau)
    public function showRegisterForm() {
        return view('auth.register');
    }

    // Dashboard Utilisateur
    public function userDashboard() {
        $user = auth()->guard()->user();
        $myReservations = $user ? $user->reservations()->latest()->get() : collect();
        return view('user.dashboard', compact('myReservations'));
    }

    // Dashboard Responsable
    public function managerDashboard() {
        $managedResources = Resource::where('manager_id', auth()->user()->id)->get();
        return view('manager.dashboard', compact('managedResources'));
    }

    // Dashboard Admin
    public function adminDashboard() {
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}