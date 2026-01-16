<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter - Gestion des Ressources</title>
    <style>
        /* CSS PERSONNALISÉ (VANILLA) */
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --bg: #f4f7f6;
            --white: #ffffff;
            --text: #333;
            --success: #27ae60;
            --danger: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            line-height: 1.6;
        }

        header {
            background: var(--primary);
            color: var(--white);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo { font-size: 1.5rem; font-weight: bold; }

        nav a, nav button {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 4px;
            transition: 0.3s;
            background: none;
            border: none;
            font-size: 1rem;
            cursor: pointer;
        }

        nav a.btn-register { background: var(--accent); }
        nav a:hover { opacity: 0.8; }

        /* --- STYLE DES NOTIFICATIONS --- */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 320px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 5px;
            overflow: hidden;
        }
        .dropdown:hover .dropdown-content { display: block; }
        
        .notif-item {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }
        .notif-item:hover { background-color: #f9f9f9; color: var(--accent); }
        .notif-time { font-size: 0.75rem; color: #888; display: block; margin-top: 4px; }
        
        .badge-count {
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            position: absolute;
            top: -8px;
            right: -10px;
        }
        /* ------------------------------- */

        .container { padding: 40px 5%; }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 5px solid var(--accent);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            background: #e0e0e0;
        }

        .status-available { color: var(--success); font-weight: bold; }

        footer { text-align: center; padding: 20px; color: #777; font-size: 0.9rem; }
    </style>
</head>
<body>

<header>
    <div class="logo">DC-Manager</div>
    <nav>
        <a href="/">Ressources</a>

        @guest
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register.request') }}" class="btn-register">Demander un compte</a>
        @else
            @php
                $unread = \App\Models\Notification::where('user_id', Auth::id())
                          ->where('is_read', false)
                          ->latest()
                          ->get();
            @endphp
            
            <div class="dropdown">
                <a href="#" style="position: relative;">
                    🔔
                    @if($unread->count() > 0)
                        <span class="badge-count">{{ $unread->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-content">
                    @if($unread->isEmpty())
                        <div style="padding: 15px; color: #777; text-align: center;">Aucune notification</div>
                    @else
                        @foreach($unread as $notif)
                            <a href="{{ $notif->link }}" class="notif-item">
                                {{ $notif->message }}
                                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @elseif(Auth::user()->role === 'manager')
                <a href="{{ route('manager.dashboard') }}">Manager</a>
            @else
                <a href="{{ route('user.dashboard') }}">Mon Espace</a>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">Déconnexion</button>
            </form>
        @endguest
    </nav>
</header>

<main class="container">
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')
</main>

<footer>
    &copy; 2026 Data Center - Gestion Interne
</footer>

</body>
</html>