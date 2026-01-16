@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px;">Espace Responsable Technique</h1>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid orange;">
        <h2 style="color: #d35400;">🔔 Demandes en attente ({{ $pendingReservations->count() }})</h2>
        
        @if($pendingReservations->isEmpty())
            <p style="color: #777; font-style: italic;">Aucune demande à traiter pour le moment.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: #f8f9fa;">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Ressource</th>
                        <th style="padding: 12px; text-align: left;">Dates</th>
                        <th style="padding: 12px; text-align: left;">Motif</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReservations as $reservation)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;">
                                <strong>{{ $reservation->user->name }}</strong><br>
                                <small>{{ $reservation->user->email }}</small>
                            </td>
                            <td style="padding: 12px; font-weight: bold; color: var(--primary);">
                                {{ $reservation->resource->label }}
                            </td>
                            <td style="padding: 12px;">
                                Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m H:i') }}<br>
                                Au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m H:i') }}
                            </td>
                            <td style="padding: 12px; font-style: italic;">"{{ Str::limit($reservation->justification, 30) }}"</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <form action="{{ route('manager.reservations.handle', $reservation->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" style="background: var(--success); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✔</button>
                                    </form>

                                    <form action="{{ route('manager.reservations.handle', $reservation->id) }}" method="POST" onsubmit="return confirm('Refuser cette demande ?');">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" style="background: var(--danger); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✘</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h2>🛠 Mes Ressources Gérées</h2>
        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($managedResources as $resource)
                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px;">
                    <strong>{{ $resource->label }}</strong>
                    <br>
                    <span class="badge" style="background: #eee; padding: 2px 8px; border-radius: 10px; font-size: 0.8em;">{{ $resource->category }}</span>
                    <p style="font-size: 0.9em; color: #666;">{{ $resource->description }}</p>
                    <div style="margin-top: 10px; font-size: 0.8em;">
                        Statut : 
                        <span style="color: {{ $resource->status == 'available' ? 'green' : 'red' }}; font-weight: bold;">
                            {{ $resource->status }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection