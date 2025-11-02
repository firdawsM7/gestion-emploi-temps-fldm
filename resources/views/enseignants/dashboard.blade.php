<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tableau de Bord Enseignant - FLDM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
    :root {
      --primary-color: #133b85;
      --secondary-color: #1e4a9e;
      --accent-color: #4e73df;
      --light-bg: #f8f9fc;
      --text-dark: #5a5c69;
      --text-light: #858796;
      --success-color: #1cc88a;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
    }
    
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
      overflow-x: hidden;
    }

    /* Sidebar */
    #sidebar {
      width: 270px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      color: white;
      z-index: 1000;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .sidebar-logo {
      text-align: center;
      padding: 20px 15px 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .sidebar-logo img {
      max-width: 160px;
      transition: transform 0.3s ease;
    }

    .sidebar-logo img:hover {
      transform: scale(1.05);
    }

    .sidebar-item {
      display: flex;
      align-items: center;
      padding: 12px 25px;
      color: rgba(255, 255, 255, 0.85);
      font-size: 18px;
      text-decoration: none;
      transition: all 0.2s ease;
      position: relative;
    }

    .sidebar-item:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      padding-left: 30px;
    }

    .sidebar-item.active {
      background-color: rgba(255, 255, 255, 0.15);
      color: white;
      font-weight: 500;
    }

    .sidebar-item.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background-color: white;
    }

    .sidebar-submenu {
      padding-left: 40px;
      font-size: 14px;
    }

    .sidebar-item i {
      width: 20px;
      margin-right: 10px;
      text-align: center;
    }

    .footer-text {
      position: absolute;
      bottom: 15px;
      left: 0;
      width: 100%;
      text-align: center;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.6);
    }

    /* Content */
    #content {
      margin-left: 270px;
      padding: 30px 40px;
      transition: margin-left 0.3s;
      animation: fadeSlide 0.6s ease-in-out;
    }

    @keyframes fadeSlide {
      from {
        opacity: 0;
        transform: translateY(15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin-bottom: 25px;
      padding: 10px 0;
    }

    .topbar .profil {
      display: flex;
      align-items: center;
      margin-left: 20px;
      padding: 8px 15px;
      background-color: white;
      border-radius: 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      cursor: pointer;
      transition: all 0.2s;
      position: relative;
    }

    .topbar .profil:hover {
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .topbar .profil i {
      margin-left: 8px;
      font-size: 18px;
      color: var(--accent-color);
    }

    .profile-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      padding: 10px 0;
      min-width: 180px;
      z-index: 1000;
      display: none;
    }

    .profile-dropdown.show {
      display: block;
    }

    .profile-dropdown-item {
      padding: 8px 15px;
      color: var(--text-dark);
      text-decoration: none;
      display: block;
      transition: background-color 0.2s;
      font-size: 14px;
    }

    .profile-dropdown-item:hover {
      background-color: #f8f9fa;
    }

    .profile-dropdown-divider {
      height: 1px;
      background-color: #e9ecef;
      margin: 5px 0;
    }

    /* Welcome Banner */
    .welcome-box {
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      color: white;
      padding: 20px 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      font-size: 18px;
      font-weight: 500;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Alert Styles */
    .alert {
      border-radius: 10px;
      padding: 15px 20px;
      margin-bottom: 20px;
      border: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
      background-color: rgba(28, 200, 138, 0.2);
      color: var(--success-color);
    }

    .alert-danger {
      background-color: rgba(231, 74, 59, 0.2);
      color: var(--danger-color);
    }

    /* Notification Styles */
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: var(--danger-color);
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: bold;
    }

    .dropdown-notification {
      max-height: 300px;
      overflow-y: auto;
    }

    .notification-item.unread {
      background-color: rgba(78, 115, 223, 0.1);
    }

    /* Profile Box */
    .profile-box {
      max-width: 900px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      margin: auto;
      transition: transform 0.3s;
    }

    .profile-box:hover {
      transform: translateY(-5px);
    }

    .profile-header {
      background: linear-gradient(90deg, #f2f4f8, #e9ecef);
      padding: 25px;
      display: flex;
      align-items: center;
      position: relative;
    }

    .avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 35px;
      color: white;
      margin-right: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .profile-text {
      flex: 1;
    }

    .profile-text h5 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 5px;
      color: var(--primary-color);
    }

    .text-small-muted {
      margin: 0;
      font-size: 14px;
      color: var(--text-light);
    }

    .profile-info {
      padding: 30px;
      position: relative;
    }

    .profile-info table {
      width: 100%;
    }

    .profile-info td {
      padding: 14px 0;
      border-bottom: 1px solid #eaecf4;
    }

    .profile-info tr:last-child td {
      border-bottom: none;
    }

    .profile-label {
      font-weight: 600;
      color: var(--text-dark);
      width: 30%;
    }

    .profile-value {
      color: var(--text-light);
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
      box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(78, 115, 223, 0.4);
    }

    .footer-bottom {
      text-align: center;
      font-size: 13px;
      color: var(--text-light);
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #eaecf4;
    }

    /* Modal Styles */
    .modal-content {
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border: none;
    }
    
    .modal-header {
      background-color: #f8f9fc;
      border-bottom: 1px solid #e3e6f0;
      padding: 15px 20px;
    }
    
    .modal-title {
      color: var(--primary-color);
      font-weight: 600;
    }
    
    .modal-body {
      padding: 20px;
    }
    
    .modal-body .form-group {
      margin-bottom: 1.5rem;
    }
    
    .modal-footer {
      border-top: 1px solid #e3e6f0;
      padding: 15px 20px;
    }
    
    .modal-footer .btn {
      padding: 8px 20px;
      border-radius: 8px;
    }

    /* Responsive */
    @media (max-width: 992px) {
      #sidebar {
        width: 80px;
        overflow: hidden;
      }
      
      #sidebar .sidebar-logo img {
        max-width: 40px;
      }
      
      #sidebar .sidebar-item span {
        display: none;
      }
      
      #sidebar .sidebar-submenu {
        padding-left: 25px;
      }
      
      #content {
        margin-left: 80px;
        padding: 20px;
      }
    }

    @media (max-width: 768px) {
      #content {
        padding: 15px;
        margin-left: 0;
      }
      
      #sidebar {
        left: -80px;
      }
      
      .topbar {
        flex-direction: column;
        gap: 10px;
      }
      
      .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 20px;
      }
      
      .avatar {
        margin-right: 0;
        margin-bottom: 15px;
      }
      
      .btn-primary-custom {
        position: static;
        margin-top: 20px;
        width: 100%;
        justify-content: center;
      }
      
      .profile-info {
        padding: 20px;
      }
      
      .profile-label {
        width: 40%;
      }
    }
  </style>
</head>
<body>
<div id="sidebar">
  <div class="sidebar-logo">
            <img src="{{ asset('/images/USMBA (3).png') }}" width="160" alt="Logo FLDM" class="img-fluid" loading="lazy">
  </div>
  <a href="{{ route('enseignant.dashboard') }}" class="sidebar-item {{ request()->routeIs('enseignant.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> <span>Home</span></a>
  <a href="{{ route('enseignant.emplois-temps') }}" class="sidebar-item {{ request()->routeIs('enseignant.emplois-temps') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Emplois de temps</span></a>
  
  <!-- Menu déroulant pour Non disponibilités -->
  <a href="#non-dispo" class="sidebar-item {{ request()->routeIs('enseignant.declaration*') || request()->routeIs('enseignant.historique') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="false">
    <i class="fas fa-book"></i> <span>Non disponibilités</span>
    <i class="fas fa-chevron-down ms-auto"></i>
  </a>
  
  <div class="collapse {{ request()->routeIs('enseignant.declaration*') || request()->routeIs('enseignant.historique') ? 'show' : '' }}" id="non-dispo">
    <a href="{{ route('enseignant.declaration') }}" class="sidebar-item sidebar-submenu {{ request()->routeIs('enseignant.declaration') ? 'active' : '' }}">
      <i class="fas fa-pen"></i> <span>Declaration</span>
    </a>
    <a href="{{ route('enseignant.historique') }}" class="sidebar-item sidebar-submenu {{ request()->routeIs('enseignant.historique') ? 'active' : '' }}">
      <i class="fas fa-history"></i> <span>Historiques</span>
    </a>
  </div>
  <!-- Menu déroulant pour Rattrapages -->
<a href="#rattrapages" class="sidebar-item {{ request()->routeIs('enseignant.rattrapage*') ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="false">
  <i class="fas fa-calendar-plus"></i> <span>Rattrapages</span>
  <i class="fas fa-chevron-down ms-auto"></i>
</a>

<div class="collapse {{ request()->routeIs('enseignant.rattrapage*') ? 'show' : '' }}" id="rattrapages">
  <a href="{{ route('enseignant.rattrapage') }}" class="sidebar-item sidebar-submenu {{ request()->routeIs('enseignant.rattrapage') ? 'active' : '' }}">
    <i class="fas fa-pen"></i> <span>Déclaration</span>
  </a>
  <a href="{{ route('enseignant.rattrapage.historique') }}" class="sidebar-item sidebar-submenu {{ request()->routeIs('enseignant.rattrapage.historique') ? 'active' : '' }}">
    <i class="fas fa-history"></i> <span>Historiques</span>
  </a>
</div>
  
  <div class="footer-text">© FLDM | Tous droits réservés 2025</div>
</div>

<!-- Content -->
<div id="content">
  <!-- Topbar avec notifications - Style harmonisé -->
  <div class="topbar">
    <!-- Notifications Dropdown -->
    <div class="dropdown me-3">
      <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if(auth()->user()->unreadNotifications->count() > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ auth()->user()->unreadNotifications->count() }}
          </span>
        @endif
      </button>
      <ul class="dropdown-menu dropdown-menu-end dropdown-notification" aria-labelledby="notificationDropdown" style="min-width: 300px;">
        <li><h6 class="dropdown-header">Notifications</h6></li>
        @if(auth()->user()->notifications->count() > 0)
          @foreach(auth()->user()->notifications->take(5) as $notification)
            <li>
              <a class="dropdown-item d-flex align-items-center notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                 href="{{ $notification->data['url'] ?? '#' }}" 
                 data-notification-id="{{ $notification->id }}">
                <div class="me-2">
                  <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-{{ $notification->data['type'] ?? 'info' }}"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                  <span class="{{ $notification->read_at ? '' : 'fw-bold' }}">
                    {{ $notification->data['message'] }}
                  </span>
                </div>
              </a>
            </li>
          @endforeach
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-center small" href="{{ route('enseignant.historique') }}">Voir toutes les notifications</a></li>
        @else
          <li><span class="dropdown-item text-muted">Aucune notification</span></li>
        @endif
      </ul>
    </div>

    <!-- Profile Dropdown - Style harmonisé -->
    <div class="profil" id="profile-dropdown-trigger">
      {{ auth()->user()->name }} <i class="fas fa-user-circle"></i>
      <div class="profile-dropdown" id="profile-dropdown">
        <a href="#" class="profile-dropdown-item" id="edit-profile-dropdown-btn">
          <i class="fas fa-user-edit me-2"></i> Modifier le profil
        </a>
        <div class="profile-dropdown-divider"></div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="profile-dropdown-item" style="width: 100%; text-align: left; background: none; border: none;">
            <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Messages de notification -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Welcome Banner -->
  <div class="welcome-box">
    <h4>Bienvenue, <span>{{ auth()->user()->name }}</span> </h4>
  </div>

  <!-- Profile Box -->
  <div class="profile-box">
    <div class="profile-header">
      <div class="avatar"><i class="fas fa-user"></i></div>
      <div class="profile-text">
        <h5>{{ auth()->user()->name }}</h5>
        <p class="text-small-muted">{{ auth()->user()->email }}</p>
      </div>
    </div>

    <div class="profile-info">
      <button class="btn-primary-custom" id="edit-profile-btn"><i class="fas fa-pen"></i> Modifier</button>
      <table>
        <tr>
          <td class="profile-label">Nom complet</td>
          <td class="profile-value">{{ auth()->user()->name }}</td>
        </tr>
        <tr>
          <td class="profile-label">Adresse email</td>
          <td class="profile-value">{{ auth()->user()->email }}</td>
        </tr>
        <tr>
          <td class="profile-label">Rôle</td>
          <td class="profile-value">Enseignant</td>
        </tr>
        <tr>
          <td class="profile-label">Mot de passe</td>
          <td class="profile-value">******</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="footer-bottom">© FLDM | Tous droits réservés 2025</div>
</div>

<!-- Modal pour modifier le profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Modifier le profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProfileForm" action="{{ route('enseignant.update-profile') }}" method="POST">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
          </div>
          <div class="form-group">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
          </div>
          <div class="form-group">
            <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer le mot de passe.</small>
          </div>
          <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Sauvegarder</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Gestion du dropdown du profil
    const profileDropdownTrigger = document.getElementById('profile-dropdown-trigger');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (profileDropdownTrigger) {
      profileDropdownTrigger.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('show');
      });
    }

    // Fermer le dropdown quand on clique ailleurs
    document.addEventListener('click', function(e) {
      if (profileDropdownTrigger && !profileDropdownTrigger.contains(e.target) && profileDropdown && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
      }
    });

    // Fonction pour gérer l'ouverture du modal d'édition du profil
    document.getElementById('edit-profile-btn').addEventListener('click', function() {
      const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
      editProfileModal.show();
    });

    // Ouvrir le modal d'édition depuis le dropdown
    document.getElementById('edit-profile-dropdown-btn').addEventListener('click', function(e) {
      e.preventDefault();
      const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
      editProfileModal.show();
    });

    // Gestion des clics sur les éléments du menu
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    sidebarItems.forEach(item => {
      item.addEventListener('click', function() {
        sidebarItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Gestion des notifications
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.getAttribute('data-notification-id');
            
            // Rediriger directement vers la route GET
            window.location.href = '/enseignant/notification/' + notificationId + '/read';
        });
    });

    // Actualiser le compteur de notifications toutes les 30 secondes
    setInterval(function() {
        fetch('/api/notifications/count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.badge.bg-danger');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                    } else {
                        // Créer le badge s'il n'existe pas
                        const bell = document.querySelector('.fa-bell');
                        if (bell && bell.parentNode) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                            newBadge.textContent = data.count;
                            bell.parentNode.appendChild(newBadge);
                        }
                    }
                } else if (badge) {
                    badge.remove();
                }
            });
    }, 30000);
  });
</script>
</body>
</html>