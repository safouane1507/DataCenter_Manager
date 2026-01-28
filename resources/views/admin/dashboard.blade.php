@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Panneau d'Administration</h1>

    @if(session('success')) <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div> @endif
    @if(session('error')) <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('error') }}</div> @endif

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="text-align: center; border-top: 4px solid var(--accent);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['users_count'] }}</h3><span>Utilisateurs</span>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid var(--success);">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['resources_count'] }}</h3><span>Ressources</span>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid orange;">
            <h3 style="margin: 0; font-size: 2rem;">{{ $stats['pending_reservations'] }}</h3><span>En attente</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px; margin-bottom: 40px;">
        <div class="card" style="padding: 20px;"><h3 style="text-align: center;">📊 État Réservations</h3><div style="height:250px;"><canvas id="statusChart"></canvas></div></div>
        <div class="card" style="padding: 20px;"><h3 style="text-align: center;">🖥️ Types Ressources</h3><div style="height:250px;"><canvas id="resourceChart"></canvas></div></div>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #d63031;">
        <h2 style="color: #d63031;">🚨 Incidents Signalés</h2>
        @if($incidents->isEmpty()) <p style="color: var(--text-muted);">R.A.S. Aucun incident.</p> @else
        <table style="width: 100%;">
            @foreach($incidents as $inc)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><b>{{ $inc->subject }}</b><br><small>{{ $inc->user->name }}</small></td>
                <td style="padding: 10px;">{{ Str::limit($inc->message, 50) }}</td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.incidents.resolve', $inc->id) }}" method="POST">@csrf <button type="submit" style="background:#2ecc71; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Résoudre</button></form>
                </td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h2>📜 Historique</h2>
            <form action="{{ route('admin.dashboard') }}" method="GET" style="display:flex; gap:10px;">
                <input type="date" name="date_start" value="{{ request('date_start') }}" style="padding:5px; border:1px solid #ddd; border-radius:4px;">
                <button type="submit" class="btn btn-primary" style="padding:5px 15px;">Filtrer</button>
            </form>
        </div>
        <table style="width:100%;">
            <thead style="background:var(--bg-background);"><tr><th style="padding:10px; text-align:left;">Date</th><th style="padding:10px; text-align:left;">User</th><th style="padding:10px; text-align:left;">Ressource</th><th style="padding:10px;">Statut</th></tr></thead>
            @foreach($history as $h)
            <tr style="border-bottom:1px solid var(--border);">
                <td style="padding:10px;">{{ $h->created_at->format('d/m/Y') }}</td>
                <td style="padding:10px;">{{ $h->user->name }}</td>
                <td style="padding:10px;">{{ $h->resource->label }}</td>
                <td style="padding:10px; text-align:center;"><span style="padding:2px 8px; border-radius:4px; background:{{ $h->status=='approved'?'#e8f5e9':($h->status=='rejected'?'#ffebee':'#fff3e0') }}; color:{{ $h->status=='approved'?'green':($h->status=='rejected'?'red':'orange') }};">{{ ucfirst($h->status) }}</span></td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Demandes Spéciales</h2>
        @if($customRequests->isEmpty()) <p style="color: #777;">Aucune demande.</p> @else
        <table style="width: 100%;">
            @foreach($customRequests as $req)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><b>{{ $req->name }}</b><br><small>{{ $req->type }}</small></td>
                <td style="padding: 10px;">CPU: {{ $req->cpu }} | RAM: {{ $req->ram }}</td>
                <td style="padding: 10px;">
                    <div style="display: flex; gap: 5px;">
                        <form action="{{ route('admin.custom.approve', $req->id) }}" method="POST">@csrf <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✔</button></form>
                        <form action="{{ route('admin.custom.reject', $req->id) }}" method="POST">@csrf <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✘</button></form>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <h2>👥 Utilisateurs</h2>
        <table style="width: 100%;">
            <tr style="background: var(--bg-background);"><th style="padding: 10px; text-align: left;">Nom</th><th style="padding: 10px; text-align: left;">Rôle</th><th style="padding: 10px; text-align: left;">Action</th></tr>
            @foreach($allUsers as $user)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $user->name }}<br><small>{{ $user->email }}</small></td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.users.role', $user->id) }}" method="POST">
                        @csrf <select name="role" onchange="this.form.submit()" style="padding: 5px; border: 1px solid #ddd; border-radius: 4px;"><option value="user" {{ $user->role=='user'?'selected':'' }}>User</option><option value="manager" {{ $user->role=='manager'?'selected':'' }}>Manager</option><option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option></select>
                    </form>
                </td>
                <td style="padding: 10px;">
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                        @csrf <button type="submit" style="background: {{ $user->is_active ? '#e74c3c' : '#2ecc71' }}; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">{{ $user->is_active ? 'Désactiver' : 'Activer' }}</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="card">
        <h2>➕ Ajouter Pack</h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; gap: 10px; grid-template-columns: 1fr 1fr;">
            @csrf
            <input type="text" name="label" placeholder="Nom" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <select name="category" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="Serveur Physique">Serveur Physique</option><option value="Machine Virtuelle">Machine Virtuelle</option><option value="Stockage">Stockage</option><option value="Réseau">Réseau</option>
            </select>
            <select name="manager_id" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="" disabled selected>Responsable...</option>
                @foreach($managers as $manager) <option value="{{ $manager->id }}">{{ $manager->name }}</option> @endforeach
            </select>
            <input type="text" name="location" placeholder="Localisation" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <input type="text" name="description" placeholder="Description" style="grid-column: span 2; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <button type="submit" style="grid-column: span 2; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Ajouter</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusData = {!! json_encode($chartData['status']) !!};
        const resourceData = {!! json_encode($chartData['resources']) !!};

        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: Object.keys(statusData), datasets: [{ data: Object.values(statusData), backgroundColor: ['#f1c40f', '#2ecc71', '#e74c3c'] }] },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('resourceChart').getContext('2d'), {
            type: 'bar',
            data: { labels: Object.keys(resourceData), datasets: [{ label: 'Quantité', data: Object.values(resourceData), backgroundColor: '#3498db' }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endsection