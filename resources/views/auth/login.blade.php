@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: var(--primary);">Connexion</h2>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px; font-size: 0.9em;">
            {{ $errors->first() }}
        </div>
    @endif
    
    @if (session('success'))
        <div style="color: green; margin-bottom: 15px; font-size: 0.9em;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label>Mot de passe</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: var(--accent); color: white; border: none; border-radius: 4px; cursor: pointer;">
            Se connecter
        </button>
    </form>
</div>
@endsection