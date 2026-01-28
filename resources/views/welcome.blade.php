@extends('layouts.app')

@section('content')

<style>
    /* Effet de lueur verte et animation au survol */
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        /* Ombre verte (Green Glow) */
        box-shadow: 0 10px 30px rgba(46, 204, 113, 0.15); 
        /* Légère teinte verte en fond */
        background: linear-gradient(145deg, var(--bg-surface), rgba(46, 204, 113, 0.05));
        border-color: #2ecc71;
    }

    /* Icônes animées */
    .feature-icon {
        transition: transform 0.3s ease;
    }
    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(5deg);
    }
</style>

<section style="text-align: center; padding: 120px 20px 100px; background: linear-gradient(180deg, var(--bg-surface) 0%, var(--bg-background) 100%); border-bottom: 1px solid var(--border); position: relative;">
    
    <div style="position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 600px; height: 600px; background: var(--primary); opacity: 0.03; border-radius: 50%; filter: blur(100px); pointer-events: none;"></div>

    <div style="max-width: 800px; margin: 0 auto; position: relative; z-index: 1;">
        <h1 style="font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; color: var(--text-primary); letter-spacing: -1px;">
            Infrastructure pour <br>
            <span style="color: var(--primary);">Bâtisseurs & Innovateurs</span>
        </h1>
        <p style="font-size: 1.25rem; color: var(--text-muted); max-width: 650px; margin: 0 auto 40px auto; line-height: 1.6;">
            La façon la plus simple de gérer, réserver et déployer vos ressources internes. 
            Obtenez la puissance nécessaire pour vos projets instantanément.
        </p>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="{{ route('resources.all') }}" class="btn btn-primary" style="padding: 16px 36px; font-size: 1.1rem; box-shadow: 0 4px 15px rgba(15, 163, 163, 0.3);">
                Explorer l'Inventaire
            </a>
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 16px 36px; font-size: 1.1rem;">
                    Espace Membre
                </a>
            @endguest
        </div>
    </div>
</section>

<section id="features" style="padding: 100px 20px; background: var(--bg-surface); border-bottom: 1px solid var(--border);">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 15px; color: var(--text-primary);">Pourquoi cette plateforme ?</h2>
            <p style="font-size: 1.1rem; color: var(--text-muted);">Une gestion centralisée pour réduire les frictions et accélérer le déploiement.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <div class="card feature-card" style="padding: 35px; border-radius: 16px;">
                <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 20px;">⚡</div>
                <h3 style="margin-bottom: 15px; font-size: 1.3rem;">Disponibilité Instantanée</h3>
                <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
                    Vérifiez l'état en temps réel des serveurs et des machines virtuelles. Plus besoin de deviner si une ressource est libre ou occupée.
                </p>
            </div>

            <div class="card feature-card" style="padding: 35px; border-radius: 16px;">
                <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 20px;">🛡️</div>
                <h3 style="margin-bottom: 15px; font-size: 1.3rem;">Accès Sécurisé & Rôles</h3>
                <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
                    Un environnement cloisonné où les managers valident les demandes. Chaque utilisateur accède uniquement à ses ressources réservées.
                </p>
            </div>

            <div class="card feature-card" style="padding: 35px; border-radius: 16px;">
                <div class="feature-icon" style="font-size: 2.5rem; margin-bottom: 20px;">📊</div>
                <h3 style="margin-bottom: 15px; font-size: 1.3rem;">Pilotage Centralisé</h3>
                <p style="font-size: 1rem; color: var(--text-muted); line-height: 1.6; margin: 0;">
                    Suivez vos réservations en cours, consultez l'historique de vos projets et recevez des notifications, le tout sur un seul écran.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="definitions" style="padding: 100px 20px; background: var(--bg-background); border-bottom: 1px solid var(--border);">
    <div style="max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 80px; align-items: center;">
        
        <div>
            <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 20px; color: var(--text-primary);">Comprendre nos Ressources</h2>
            <p style="font-size: 1.1rem; color: var(--text-muted); line-height: 1.7; margin-bottom: 35px;">
                Vous ne savez pas exactement de quel équipement vous avez besoin pour votre projet ?<br>
                Voici un guide rapide des technologies disponibles dans notre DataCenter.
            </p>
            <a href="{{ route('resources.all') }}" class="btn btn-outline" style="padding: 12px 30px; font-weight: 600;">
                Voir tout le catalogue &rarr;
            </a>
        </div>

        <div style="display: grid; gap: 25px;">
            <div class="feature-card" style="padding: 25px; border-radius: 12px; background: var(--bg-surface); border-left: 5px solid var(--primary);">
                <h4 style="margin: 0 0 8px 0; font-size: 1.2rem; color: var(--text-primary);">☁️ Machine Virtuelle (VM)</h4>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">Un ordinateur simulé par logiciel. Idéal pour le développement, les tests et les applications web légères.</p>
            </div>

            <div class="feature-card" style="padding: 25px; border-radius: 12px; background: var(--bg-surface); border-left: 5px solid var(--primary);">
                <h4 style="margin: 0 0 8px 0; font-size: 1.2rem; color: var(--text-primary);">🖥️ Serveur Physique (Bare Metal)</h4>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">La puissance brute du matériel sans couche de virtualisation. Pour les calculs intensifs et les grosses bases de données.</p>
            </div>

            <div class="feature-card" style="padding: 25px; border-radius: 12px; background: var(--bg-surface); border-left: 5px solid var(--primary);">
                <h4 style="margin: 0 0 8px 0; font-size: 1.2rem; color: var(--text-primary);">💾 Stockage Bloc (SAN/NAS)</h4>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">Espace disque haute performance et évolutif. Attachez-le à vos VMs pour stocker vos données de manière persistante.</p>
            </div>
        </div>
    </div>
</section>

<section style="padding: 120px 20px; text-align: center; background: var(--bg-surface);">
    <div style="max-width: 700px; margin: 0 auto; padding: 60px; border-radius: 24px; background: linear-gradient(135deg, var(--bg-background) 0%, var(--bg-surface) 100%); border: 1px solid var(--border); box-shadow: 0 20px 40px rgba(0,0,0,0.05);">
        <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 15px; color: var(--text-primary);">Besoin d'une configuration spéciale ?</h2>
        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 40px; line-height: 1.6;">
            Notre équipe technique est disponible pour vous accompagner dans le dimensionnement de votre infrastructure sur mesure.
        </p>
        <a href="mailto:support@datacenter.com" class="btn btn-primary" style="padding: 16px 40px; font-weight: bold; font-size: 1.1rem; border-radius: 50px;">
            Contacter le Support Technique
        </a>
    </div>
</section>

@endsection