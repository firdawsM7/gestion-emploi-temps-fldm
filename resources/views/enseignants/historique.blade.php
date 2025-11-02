<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Historique des déclarations - FLDM</title>
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
      --info-color: #36b9cc;
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

    .alert-info {
      background-color: rgba(54, 185, 204, 0.2);
      color: var(--info-color);
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

    /* Form container */
    .form-container {
      max-width: 900px;
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      margin: auto;
      padding: 30px;
    }

    .form-title {
      font-size: 22px;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 25px;
      text-align: center;
    }

    .btn-submit-custom {
      background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s;
      width: 100%;
      box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .btn-submit-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(78, 115, 223, 0.4);
    }
    
    .periode-fields {
      display: none;
    }

    /* Table Container */
    .table-container {
      background-color: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      transition: transform 0.3s;
    }

    .table-container:hover {
      transform: translateY(-5px);
    }

    h3 {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eaecf4;
    }

    .search-filter-container {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    .search-filter-container input,
    .search-filter-container select {
      border-radius: 8px;
      border: 1px solid #d1d3e2;
      padding: 10px 15px;
      transition: all 0.2s;
    }

    .search-filter-container input:focus,
    .search-filter-container select:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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

    .table {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
    }

    .table thead th {
      background-color: #f8f9fc;
      color: var(--primary-color);
      font-weight: 600;
      padding: 15px;
      border-bottom: 2px solid #e3e6f0;
      vertical-align: middle;
    }

    .table tbody td {
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid #e3e6f0;
    }

    .table tbody tr {
      transition: all 0.2s;
    }

    .table tbody tr:hover {
      background-color: #f8f9fc;
      transform: scale(1.005);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.03);
    }

    .statut-badge {
      padding: 7px 14px;
      border-radius: 50px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
      text-align: center;
      min-width: 100px;
    }

    .statut-en_attente {
      background-color: rgba(246, 194, 62, 0.2);
      color: var(--warning-color);
    }

    .statut-approuve {
      background-color: rgba(28, 200, 138, 0.2);
      color: var(--success-color);
    }

    .statut-rejete {
      background-color: rgba(231, 74, 59, 0.2);
      color: var(--danger-color);
    }

    .btn-action {
      padding: 6px 10px;
      font-size: 13px;
      margin-right: 5px;
      border-radius: 6px;
      transition: all 0.2s;
    }

    .btn-action:hover {
      transform: translateY(-2px);
    }

    .btn-warning {
      background-color: var(--warning-color);
      border-color: var(--warning-color);
      color: white;
    }

    .btn-danger {
      background-color: var(--danger-color);
      border-color: var(--danger-color);
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #6e707e;
    }

    .empty-state i {
      font-size: 60px;
      margin-bottom: 15px;
      color: #dddfeb;
    }

    .empty-state p {
      font-size: 16px;
      margin-bottom: 20px;
    }

    .footer-bottom {
      text-align: center;
      font-size: 13px;
      color: var(--text-light);
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #eaecf4;
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
      
      .search-filter-container {
        flex-direction: column;
      }
      
      .table-responsive {
        overflow-x: auto;
      }
      
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
<div id="sidebar">
  <div class="sidebar-logo">
  <img src="{{ asset('/images/USMBA (3).png') }}" width="160" alt="Logo FLDM" class="img-fluid" loading="lazy">
  </div>
  <a href="{{ route('enseignant.dashboard') }}" class="sidebar-item"><i class="fas fa-home"></i> <span>Home</span></a>
  <a href="{{ route('enseignant.emplois-temps') }}" class="sidebar-item"><i class="fas fa-calendar-alt"></i> <span>Emplois de temps</span></a>
  
  <!-- Menu déroulant pour Non disponibilités -->
  <a href="#non-dispo" class="sidebar-item" data-bs-toggle="collapse" aria-expanded="false">
    <i class="fas fa-book"></i> <span>Non disponibilités</span>
    <i class="fas fa-chevron-down ms-auto"></i>
  </a>
  
  <div class="collapse show" id="non-dispo">
    <a href="{{ route('enseignant.declaration') }}" class="sidebar-item sidebar-submenu">
      <i class="fas fa-pen"></i> <span>Declaration</span>
    </a>
    <a href="{{ route('enseignant.historique') }}" class="sidebar-item sidebar-submenu active">
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
                 href="/enseignant/notification/{{ $notification->id }}/read" 
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

    <div class="profil" id="profile-dropdown-trigger">
      {{ auth()->user()->name }} <i class="fas fa-user-circle"></i>
      <div class="profile-dropdown" id="profile-dropdown">
        <a href="{{ route('enseignant.dashboard') }}" class="profile-dropdown-item">
          <i class="fas fa-user me-2"></i> Mon profil
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
    <h4>Historique des déclarations, <span>{{ auth()->user()->name }}</span> </h4>
  </div>

  <!-- Table Container -->
  <div class="table-container">
    <h3>Historique des déclarations de non-disponibilité</h3>
    
    <!-- Formulaire de recherche et filtre -->
    <form method="GET" action="{{ route('enseignant.historique') }}">
      <div class="search-filter-container">
        <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
               value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
        <select name="filter" class="form-select" style="width: 200px;">
          <option value="tous" {{ request('filter') == 'tous' ? 'selected' : '' }}>Tous les statuts</option>
          <option value="en_attente" {{ request('filter') == 'en_attente' ? 'selected' : '' }}>En attente</option>
          <option value="approuve" {{ request('filter') == 'approuve' ? 'selected' : '' }}>Approuvé</option>
          <option value="rejete" {{ request('filter') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
        </select>
        <button type="submit" class="btn btn-primary-custom">Filtrer</button>
        <a href="{{ route('enseignant.historique') }}" class="btn btn-secondary" style="padding: 10px 20px; border-radius: 8px;">
          Réinitialiser
        </a>
      </div>
    </form>

    @if($historiques->isEmpty())
      <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>Aucune déclaration trouvée.</p>
        <a href="{{ route('enseignant.declaration') }}" class="btn btn-primary-custom">
          <i class="fas fa-plus me-2"></i>Faire une déclaration
        </a>
      </div>
    @else
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Période</th>
              <th>Type</th>
              <th>Raison</th>
              <th>Statut</th>
              <th>Date de création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($historiques as $declaration)
              <tr>
                <td>
                  @if($declaration->date_debut->format('Y-m-d') === $declaration->date_fin->format('Y-m-d'))
                    {{ $declaration->date_debut->format('d/m/Y') }}
                  @else
                    {{ $declaration->date_debut->format('d/m/Y') }} - {{ $declaration->date_fin->format('d/m/Y') }}
                  @endif
                  @if($declaration->isPeriodeSpecifique())
                    <br><small class="text-muted">{{ $declaration->periode }}</small>
                  @endif
                </td>
                <td>
                  {{ $declaration->isJourneeComplete() ? 'Journée complète' : 'Période spécifique' }}
                </td>
                <td>{{ Str::limit($declaration->raison, 50) }}</td>
                <td>
                  <span class="statut-badge statut-{{ $declaration->statut }}">
                    {{ ucfirst($declaration->statut) }}
                  </span>
                </td>
                <td>{{ $declaration->created_at->format('d/m/Y H:i') }}</td>
                <td>
                  @if($declaration->statut === 'en_attente')
                    <a href="{{ route('enseignant.declaration.edit', $declaration->id) }}" 
                       class="btn btn-sm btn-warning btn-action" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('enseignant.declaration.delete', $declaration->id) }}" 
                          method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger btn-action" 
                              onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ?')"
                              title="Supprimer">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  @else
                    <span class="text-muted">Aucune action</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <div class="footer-bottom">© FLDM | Tous droits réservés 2025</div>
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