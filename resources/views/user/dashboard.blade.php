@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Mon Espace Utilisateur</h1>
        <a href="{{ route('reservations.create') }}" style="background: var(--accent); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            + Nouvelle Réservation
        </a>
    </div>

    <div class="card">
        <h3>Mes Réservations Récentes</h3>
        @if($myReservations->isEmpty())
            <p style="color: #777; font-style: italic;">Vous n'avez aucune réservation pour le moment.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 10px;">Ressource</th>
                        <th style="padding: 10px;">Dates</th>
                        <th style="padding: 10px;">Statut</th>
                        <th style="padding: 10px;">Justification</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myReservations as $reservation)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px; font-weight: bold;">{{ $reservation->resource->label }}</td>
                            <td style="padding: 10px;">
                                Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y H:i') }}<br>
                                Au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y H:i') }}
                            </td>
                            <td style="padding: 10px;">
                                @if($reservation->status == 'pending')
                                    <span style="color: orange; font-weight: bold;">En attente</span>
                                @elseif($reservation->status == 'approved')
                                    <span style="color: green; font-weight: bold;">Approuvée</span>
                                @elseif($reservation->status == 'rejected')
                                    <span style="color: red; font-weight: bold;">Refusée</span>
                                @endif
                            </td>
                            <td style="padding: 10px; color: #555;">{{ Str::limit($reservation->justification, 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection