@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Panneau d'Administration</h1>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="text-align: center; padding: 20px; border-top: 4px solid var(--accent);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['users_count'] }}</h3>
            <span>Utilisateurs</span>
        </div>
        <div class="card" style="text-align: center; padding: 20px; border-top: 4px solid var(--success);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['resources_count'] }}</h3>
            <span>Ressources</span>
        </div>
        <div class="card" style="text-align: center; padding: 20px; border-top: 4px solid orange;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['pending_reservations'] }}</h3>
            <span>En attente</span>
        </div>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Demandes sur Mesure ({{ $customRequests->count() }})</h2>
        @if($customRequests->isEmpty())
            <p style="color: #777;">Aucune demande spéciale en attente.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left;">
                        <th style="padding: 10px;">Utilisateur</th>
                        <th style="padding: 10px;">Besoin</th>
                        <th style="padding: 10px;">Justification</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customRequests as $req)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><b>{{ $req->name }}</b><br><small>{{ $req->email }}</small></td>
                            <td style="padding: 10px;">{{ $req->type }} <br> <small>{{ $req->cpu }} / {{ $req->ram }}</small></td>
                            <td style="padding: 10px;"><i>"{{ Str::limit($req->justification, 30) }}"</i></td>
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    <form action="{{ route('admin.custom.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>
                                    <form action="{{ route('admin.custom.reject', $req->id) }}" method="POST" onsubmit="return confirm('Rejeter ?');">
                                        @csrf
                                        <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✘ Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid orange;">
        <h2 style="color: #d35400;">📅 Réservations en attente ({{ $pendingReservations->count() }})</h2>
        @if($pendingReservations->isEmpty())
            <p style="color: #777;">Aucune réservation en attente.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: #f8f9fa; text-align: left;">
                        <th style="padding: 10px;">Utilisateur</th>
                        <th style="padding: 10px;">Ressource</th>
                        <th style="padding: 10px;">Dates</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReservations as $reservation)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><b>{{ $reservation->user->name }}</b></td>
                            <td style="padding: 10px; color: var(--primary);">{{ $reservation->resource->label }}</td>
                            <td style="padding: 10px;">
                                Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m') }} <br>
                                Au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m') }}
                            </td>
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    <form action="{{ route('admin.reservations.handle', $reservation->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>
                                    <form action="{{ route('admin.reservations.handle', $reservation->id) }}" method="POST" onsubmit="return confirm('Refuser ?');">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✘ Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <h2>👥 Gestion des Utilisateurs</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #f8f9fa; text-align: left;">
                    <th style="padding: 10px;">Nom</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Rôle</th>
                    <th style="padding: 10px;">Statut</th>
                    <th style="padding: 10px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allUsers as $user)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;">{{ $user->name }}</td>
                        <td style="padding: 10px;">{{ $user->email }}</td>
                        
                        <td style="padding: 10px;">
                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                                @csrf
                                <select name="role" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc; background: #fff; cursor: pointer;">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                    <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>

                        <td style="padding: 10px; color: {{ $user->is_active ? 'green' : 'red' }}; font-weight: bold;">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </td>
                        <td style="padding: 10px;">
                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background: {{ $user->is_active ? '#e74c3c' : '#2ecc71' }}; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>➕ Ajouter un Pack (Admin)</h2>
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Le pack sera assigné au manager sélectionné, qui pourra ensuite le gérer ou le supprimer.</p>
        
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; gap: 15px; grid-template-columns: 1fr 1fr;">
            @csrf
            <input type="text" name="label" placeholder="Nom du pack (ex: Serveur Gold)" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            
            <select name="category" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="Serveur Physique">Serveur Physique</option>
                <option value="Machine Virtuelle">Machine Virtuelle</option>
                <option value="Stockage">Stockage</option>
                <option value="Réseau">Réseau</option>
            </select>
            
            <select name="manager_id" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fff;">
                <option value="" disabled selected>Assigner à un Responsable...</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }} ({{ ucfirst($manager->role) }})</option>
                @endforeach
            </select>

            <input type="text" name="location" placeholder="Localisation" required style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            
            <input type="text" name="description" placeholder="Description courte" style="grid-column: span 2; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            
            <button type="submit" style="grid-column: span 2; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1rem;">Ajouter au catalogue</button>
        </form>
    </div>
</div>
@endsection