@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <h1>Administration du Data Center</h1>

    <div class="grid" style="margin-bottom: 40px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div class="card" style="text-align: center; border-top: 4px solid var(--accent); padding: 20px;">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['users_count'] }}</div>
            <div>Utilisateurs Inscrits</div>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid var(--success); padding: 20px;">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['resources_count'] }}</div>
            <div>Ressources Totales</div>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid orange; padding: 20px;">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['pending_reservations'] }}</div>
            <div>Réservations en attente</div>
        </div>
    </div>

    @if($pendingUsers->isNotEmpty())
        <div class="card" style="margin-bottom: 30px; border-left: 5px solid red;">
            <h2 style="color: #c0392b;">👤 Comptes en attente de validation</h2>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: #f9f9f9;">
                    <tr>
                        <th style="padding: 10px; text-align: left;">Nom</th>
                        <th style="padding: 10px; text-align: left;">Email</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                        <th style="padding: 10px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">{{ $user->name }}</td>
                            <td style="padding: 10px;">{{ $user->email }}</td>
                            <td style="padding: 10px;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 10px; text-align: right;">
                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: var(--success); color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">
                                        Activer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card" style="margin-bottom: 30px;">
        <h2>Tous les Utilisateurs</h2>
        <table style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allUsers as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        @if($user->is_active) <span style="color: green;">Actif</span>
                        @else <span style="color: red;">Inactif</span> @endif
                    </td>
                    <td>
                        <form action="{{ url('/admin/users/'.$user->id.'/toggle') }}" method="POST"> @csrf
                            <button style="font-size: 0.8rem; padding: 2px 5px;">
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
        <h2>➕ Ajouter une Ressource</h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 5px;">Nom de la ressource</label>
                <input type="text" name="label" required style="width: 100%; padding: 8px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px;">Catégorie</label>
                <select name="category" required style="width: 100%; padding: 8px;">
                    <option value="Serveur">Serveur Physique</option>
                    <option value="VM">Machine Virtuelle</option>
                    <option value="Stockage">Baie de Stockage</option>
                    <option value="Réseau">Switch / Routeur</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px;">Responsable Technique</label>
                <select name="manager_id" required style="width: 100%; padding: 8px;">
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px;">Emplacement</label>
                <input type="text" name="location" placeholder="Ex: Baie A2" style="width: 100%; padding: 8px;">
            </div>
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px;">Description</label>
                <textarea name="description" rows="2" style="width: 100%; padding: 8px;"></textarea>
            </div>
            <div style="grid-column: span 2;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%;">
                    Enregistrer la ressource
                </button>
            </div>
        </form>
    </div>
</div>
@endsection