<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord | FLDM</title>
    
    <!-- Preload des assets -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" as="script">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
    :root {
        --sidebar-width: 250px;
        --primary-color: #1a237e;
        --secondary-color: #283593;
        --accent-color: #5c6bc0;
        --text-light: #e8eaf6;
        --text-dark: #1a237e;
        --card-bg: #ffffff;
        --card-hover: #f5f5f5;
        --success-color: #4caf50;
        --info-color: #3f37c9;
        --warning-color: #ff9800;
        --danger-color: #f44336;
    }
    
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
        color: #333;
    }
    
    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        height: 100vh;
        position: fixed;
        transition: transform 0.3s ease;
        box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        z-index: 1030;
    }
    
    .sidebar .nav-link {
        color: var(--text-light);
        opacity: 0.8;
        border-radius: 6px;
        margin: 0.25rem 0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(255,255,255,0.15);
        opacity: 1;
        transform: translateX(5px);
    }
    
    .sidebar .nav-link.active {
        background-color: var(--accent-color);
        font-weight: 600;
        opacity: 1;
        box-shadow: 0 4px 12px rgba(92, 107, 192, 0.3);
    }
    
    .topbar {
        height: 70px;
        background-color: white;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 0 1.5rem;
        margin-left: var(--sidebar-width);
        position: sticky;
        top: 0;
        z-index: 1020;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 2rem;
        min-height: calc(100vh - 70px);
        background-color: #f5f7fa;
    }
    
    .stat-card {
        background: var(--card-bg);
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        padding: 1.75rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        border: none;
        position: relative;
        overflow: hidden;
        border-left: 4px solid var(--accent-color);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        background: var(--card-hover);
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 1.25rem;
        object-fit: contain;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.1));
    }
    
    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0.75rem 0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }
    
    .btn-outline-primary {
        border-color: var(--accent-color);
        color: var(--accent-color);
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background-color: var(--accent-color);
        color: white;
    }
    
    .btn-primary {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #3949ab;
        border-color: #3949ab;
        transform: translateY(-2px);
    }
    
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .fa-chevron-down {
        transition: transform 0.3s ease;
    }
    
    .fa-chevron-down.rotate-180 {
        transform: rotate(180deg);
    }
    
    .main-content.blur {
        filter: blur(2px);
        pointer-events: none;
    }
    
    .text-success { color: var(--success-color) !important; }
    .text-primary { color: var(--info-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-danger { color: var(--danger-color) !important; }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-dropdown {
        width: 350px;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .notification-header {
        background-color: white;
    }

    .notification-body .dropdown-item {
        white-space: normal;
        transition: background-color 0.2s;
    }

    .notification-body .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .notification-footer {
        background-color: #f8f9fa;
    }

    .user-dropdown .dropdown-menu {
        min-width: 200px;
        border: none;
        border-radius: 12px;
    }

    .badge-notification {
        font-size: 0.6rem;
        padding: 0.25em 0.4em;
    }
    
    @media (max-width: 992px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .topbar,
        .main-content {
            margin-left: 0;
        }
        
        .topbar {
            justify-content: flex-start !important;
        }
    }
</style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <div class="text-center mb-4">
            <img src="{{ asset('/images/USMBA (3).png') }}" width="160" alt="Logo FLDM" class="img-fluid" loading="lazy">
        </div>
        
        <button class="navbar-toggler d-lg-none text-white mb-3 align-self-center" type="button" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        
        <ul class="nav nav-pills flex-column flex-grow-1">
            <li class="nav-item mb-2">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">
                    <i class="fas fa-home me-2"></i> Tableau de bord
                </a>
            </li>
            
            <!-- Menu Admin pour les déclarations -->
            @if(auth()->user()->id_role == 1)
            <li class="nav-item mb-2">
                <a href="{{ route('admin.declarations') }}" class="nav-link {{ request()->routeIs('admin.declarations') ? 'active' : '' }}" aria-current="{{ request()->routeIs('admin.declarations') ? 'page' : 'false' }}">
                    <i class="fas fa-clipboard-list me-2"></i> Déclarations
                </a>
            </li>
            
            <!-- Menu Admin pour les rattrapages -->
            <li class="nav-item mb-2">
                <a href="{{ route('admin.rattrapages') }}" class="nav-link {{ request()->routeIs('admin.rattrapages') ? 'active' : '' }}" aria-current="{{ request()->routeIs('admin.rattrapages') ? 'page' : 'false' }}">
                    <i class="fas fa-calendar-plus me-2"></i> Rattrapages
                </a>
            </li>
            @endif
            
            <li class="nav-item mb-2">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#emploiCollapse" aria-expanded="false" aria-controls="emploiCollapse">
                    <i class="fas fa-calendar-alt me-2"></i> Emplois du temps
                    <i class="fas fa-chevron-down ms-auto small transition-all"></i>
                </a>
                <div class="collapse ps-3" id="emploiCollapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('emplois.consulter') }}" class="nav-link {{ request()->routeIs('emplois.consulter') ? 'active' : '' }}">
                                <i class="fas fa-search me-2"></i> Consulter
                            </a>
                        </li>
                        @if(auth()->user()->id_role == 1)
                        <li class="nav-item">
                            <a href="{{ route('emplois.ajouter') }}" class="nav-link {{ request()->routeIs('emplois.ajouter') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle me-2"></i> Ajouter
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            
            <!-- Menu Enseignant pour les non-disponibilités -->
            @if(auth()->user()->id_role == 2)
            <li class="nav-item mb-2">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#nonDispoCollapse" aria-expanded="false" aria-controls='nonDispoCollapse'>
                    <i class="fas fa-calendar-times me-2"></i> Non-disponibilités
                    <i class="fas fa-chevron-down ms-auto small transition-all"></i>
                </a>
                <div class="collapse ps-3" id="nonDispoCollapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('enseignant.declaration') }}" class="nav-link {{ request()->routeIs('enseignant.declaration') ? 'active' : '' }}">
                                <i class="fas fa-pen me-2"></i> Déclaration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('enseignant.historique') }}" class="nav-link {{ request()->routeIs('enseignant.historique') ? 'active' : '' }}">
                                <i class="fas fa-history me-2"></i> Historique
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            
            <!-- Menu Admin seulement -->
            @if(auth()->user()->id_role == 1)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('modules.*') ? 'active' : '' }}" href="{{ route('modules.index') }}" aria-current="{{ request()->routeIs('modules.*') ? 'page' : 'false' }}">
                    <i class="fas fa-book me-2"></i> Modules
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('filieres.*') ? 'active' : '' }}" href="{{ route('filieres.index') }}" aria-current="{{ request()->routeIs('filieres.*') ? 'page' : 'false' }}">
                    <i class="fas fa-graduation-cap me-2"></i> Filières
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('salles.*') ? 'active' : '' }}" href="{{ route('salles.index') }}" aria-current="{{ request()->routeIs('salles.*') ? 'page' : 'false' }}">
                    <i class="fas fa-building me-2"></i> Salles
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('departements.*') ? 'active' : '' }}" href="{{ route('departements.index') }}" aria-current="{{ request()->routeIs('departements.*') ? 'page' : 'false' }}">
                    <i class="fas fa-sitemap me-2"></i> Départements
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('enseignants.*') ? 'active' : '' }}" href="{{ route('enseignants.index') }}" aria-current="{{ request()->routeIs('enseignants.*') ? 'page' : 'false' }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i> Enseignants
                </a>
            </li>
            @endif
            
            
        </ul>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center w-100">
            <button class="navbar-toggler d-lg-none border-0 bg-transparent me-3" type="button" aria-label="Menu">
                <i class="fas fa-bars text-dark"></i>
            </button>
            
            <div class="d-flex align-items-center ms-auto">
                <!-- Notifications Dropdown -->
                <div class="position-relative me-4">
                    <button class="btn btn-link text-dark p-0 position-relative" type="button" aria-label="Notifications" data-bs-toggle="dropdown" aria-expanded="false" id="notificationsDropdown">
                        <i class="fas fa-bell fs-5"></i>
                        @php
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-notification">{{ $unreadCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 shadow">
                        <div class="notification-header p-3 border-bottom">
                            <h6 class="mb-0">Notifications ({{ $unreadCount }})</h6>
                        </div>
                        <div class="notification-body" style="max-height: 300px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <a href="{{ $notification->data['url'] ?? (auth()->user()->isAdmin() ? route('admin.declarations') : route('enseignant.historique')) }}" 
                                   class="dropdown-item border-bottom p-3" 
                                   onclick="markNotificationAsRead('{{ $notification->id }}')">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            @if(isset($notification->data['type']) && $notification->data['type'] == 'success')
                                                <i class="fas fa-check-circle text-success fs-4"></i>
                                            @elseif(isset($notification->data['type']) && $notification->data['type'] == 'error')
                                                <i class="fas fa-times-circle text-danger fs-4"></i>
                                            @else
                                                <i class="fas fa-info-circle text-primary fs-4"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mb-1">{{ $notification->data['message'] }}</p>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($notification->data['time'])->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="dropdown-item border-bottom p-3 text-center">
                                    <p class="mb-0 text-muted">Aucune notification</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="notification-footer text-center p-2">
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.declarations') : route('enseignant.historique') }}" class="text-primary">Voir toutes les notifications</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown user-dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="me-2 d-none d-sm-block text-end">
                            <div class="fw-semibold">{{ Auth::user()->name ?? 'Utilisateur' }}</div>
                            <small class="text-muted">
                                @if(auth()->user()->id_role == 1)
                                    Administrateur
                                @endif
                            </small>
                        </div>
                        <i class="fas fa-user-circle fs-3 text-primary"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby='userDropdown'>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-user me-2"></i> Profil
                            </a>
                        </li>
                        
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <!-- Section principale pour le contenu des vues enfants -->
        @yield('main')
    </div>
        
    <footer class="text-center text-muted mt-5 pt-3 border-top">
        <small>© 2025 Faculté des Lettres et des Sciences Humaines. Tous droits réservés. v2.1.0</small>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.querySelectorAll('.navbar-toggler').forEach(toggler => {
            toggler.addEventListener('click', () => {
                document.querySelector('.sidebar').classList.toggle('show');
                document.querySelector('.main-content').classList.toggle('blur');
            });
        });
        
        // Highlight current section
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    link.setAttribute('aria-current', 'page');
                }
            });
            
            // Animation for dropdown arrows
            const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
            collapseElements.forEach(el => {
                el.addEventListener('click', () => {
                    const icon = el.querySelector('.fa-chevron-down');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });

        // Marquer une notification comme lue
        function markNotificationAsRead(notificationId) {
            // Déterminer la route en fonction du rôle de l'utilisateur
            const isAdmin = {{ auth()->user()->id_role == 1 ? 'true' : 'false' }};
            const routePrefix = isAdmin ? '/admin' : '/enseignant';
            
            fetch(routePrefix + '/notification/' + notificationId + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    // Mettre à jour le compteur de notifications
                    const badge = document.querySelector('.badge-notification');
                    if (badge) {
                        const count = parseInt(badge.textContent) - 1;
                        if (count > 0) {
                            badge.textContent = count;
                        } else {
                            badge.remove();
                        }
                    }
                    
                    // Supprimer la notification de la liste
                    const notificationElement = document.querySelector(`a[onclick*="${notificationId}"]`);
                    if (notificationElement) {
                        notificationElement.remove();
                    }
                    
                    // Actualiser le compteur dans l'en-tête
                    updateNotificationCount();
                }
            });
        }

        // Fonction pour mettre à jour le compteur de notifications
        function updateNotificationCount() {
            fetch('/api/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.badge-notification');
                    const countElement = document.querySelector('.notification-header h6');
                    
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            // Créer le badge s'il n'existe pas
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-notification';
                            newBadge.textContent = data.count;
                            document.getElementById('notificationsDropdown').appendChild(newBadge);
                        }
                        
                        if (countElement) {
                            countElement.textContent = `Notifications (${data.count})`;
                        }
                    } else if (badge) {
                        badge.remove();
                        if (countElement) {
                            countElement.textContent = 'Notifications (0)';
                        }
                    }
                });
        }

        // Actualiser les notifications périodiquement (toutes les 30 secondes)
        setInterval(() => {
            updateNotificationCount();
        }, 30000);
    </script>
</body>
</html>