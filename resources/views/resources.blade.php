@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 2.5rem; font-weight: 800; color: var(--text-primary);">Catalogue</h1>
            <p style="color: var(--text-muted); margin-top: 5px;">
                @if(request('cat')) Filtré par : <strong>{{ request('cat') }}</strong> @else Toutes les ressources @endif
            </p>
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('resources.all') }}" class="btn {{ !request('cat') ? 'btn-primary' : 'btn-outline' }}">Tout</a>
            <a href="{{ route('resources.all', ['cat' => 'Serveur Physique']) }}" class="btn {{ request('cat') == 'Serveur Physique' ? 'btn-primary' : 'btn-outline' }}">Serveurs</a>
            <a href="{{ route('resources.all', ['cat' => 'Machine Virtuelle']) }}" class="btn {{ request('cat') == 'Machine Virtuelle' ? 'btn-primary' : 'btn-outline' }}">VMs</a>
            <a href="{{ route('resources.all', ['cat' => 'Stockage']) }}" class="btn {{ request('cat') == 'Stockage' ? 'btn-primary' : 'btn-outline' }}">Stockage</a>
            <a href="{{ route('resources.all', ['cat' => 'Réseau']) }}" class="btn {{ request('cat') == 'Réseau' ? 'btn-primary' : 'btn-outline' }}">Réseau</a>
        </div>
    </div>

    @if($resources->isEmpty())
        <div style="text-align: center; padding: 80px; background: var(--bg-surface); border-radius: 16px; border: 2px dashed var(--border);">
            <h3 style="color: var(--text-primary);">Aucune ressource trouvée.</h3>
            <a href="{{ route('resources.all') }}" class="btn btn-primary">Voir tout</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
            @foreach($resources as $resource)
                <div class="card" style="display: flex; flex-direction: column; transition: transform 0.2s; border: 1px solid var(--border); background: var(--bg-surface); border-radius: 12px; padding: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase;">
                            {{ $resource->category }}
                        </span>
                        <span style="font-size: 0.75rem; color: #2ecc71; font-weight: bold; background: rgba(46, 204, 113, 0.1); padding: 2px 8px; border-radius: 10px;">
                            ● Disponible
                        </span>
                    </div>
                    
                    <h3 style="margin: 0 0 10px 0; font-size: 1.3rem; color: var(--text-primary);">{{ $resource->label }}</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); flex-grow: 1; margin-bottom: 20px;">
                        {{ Str::limit($resource->description ?? 'Aucune description', 80) }}
                    </p>
                    
                    <div style="padding-top: 15px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">📍 {{ $resource->location }}</span>
                        
                        @if(!Auth::check() || Auth::user()->role === 'user')
                            <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 0.9rem;">
                                Réserver
                            </a>
                        @else
                            <span style="padding: 8px 16px; font-size: 0.8rem; background: var(--bg-background); color: var(--text-muted); border: 1px solid var(--border); border-radius: 8px; cursor: not-allowed;">
                                Réservé aux Utilisateurs
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: var(--primary); }
</style>
@endsection