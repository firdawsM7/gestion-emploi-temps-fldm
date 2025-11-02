<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Emplois du Temps - FLDM</title>
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

    /* Emploi du temps */
    .emploi-container {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      padding: 30px;
      margin-bottom: 30px;
      transition: transform 0.3s;
    }

    .emploi-container:hover {
      transform: translateY(-5px);
    }

    .emploi-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .emploi-title {
      font-size: 24px;
      font-weight: 600;
      color: var(--primary-color);
      margin: 0;
    }

    .btn-download {
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
      text-decoration: none;
    }

    .btn-download:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(78, 115, 223, 0.4);
      color: white;
    }

    .semestre-selector {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
      background-color: #f8f9fc;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #e3e6f0;
    }

    .semestre-selector label {
      font-weight: 600;
      color: var(--primary-color);
      margin: 0;
    }

    .semestre-selector select {
      border: 1px solid #d1d3e2;
      border-radius: 5px;
      padding: 8px 12px;
      background-color: white;
      color: var(--text-dark);
      font-size: 14px;
    }

    .semestre-selector button {
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 5px;
      padding: 8px 15px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .semestre-selector button:hover {
      background-color: var(--secondary-color);
    }

    .table-emploi {
      width: 100%;
      border-collapse: collapse;
    }

    .table-emploi th {
      background-color: #f2f4f8;
      padding: 15px;
      text-align: center;
      font-weight: 600;
      border: 1px solid #dee2e6;
      color: var(--primary-color);
    }

    .table-emploi td {
      padding: 15px;
      border: 1px solid #dee2e6;
      vertical-align: top;
      height: 120px;
    }

    .seance-item {
      background-color: #f8f9fc;
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 8px;
      border-left: 4px solid var(--accent-color);
      transition: all 0.2s;
    }

    .seance-item:hover {
      background-color: #f0f4ff;
      transform: translateX(2px);
    }

    .seance-module {
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 8px;
      font-size: 14px;
    }

    .seance-details {
      font-size: 12px;
      color: var(--text-light);
      line-height: 1.4;
    }

    .seance-detail-line {
      margin-bottom: 3px;
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
      
      .emploi-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .btn-download {
        width: 100%;
        justify-content: center;
      }
      
      .semestre-selector {
        flex-direction: column;
        align-items: stretch;
      }
      
      .table-emploi td {
        height: auto;
        padding: 10px;
      }
      
      .seance-item {
        padding: 8px;
      }
    }
  </style>
</head>
<body>
<!-- Sidebar -->
<div id="sidebar">
  <div class="sidebar-logo">
            <img src="{{ asset('/images/USMBA (3).png') }}" width="160" alt="Logo FLDM" class="img-fluid" loading="lazy">
  </div>
  <a href="{{ route('enseignant.dashboard') }}" class="sidebar-item"><i class="fas fa-home"></i> <span>Home</span></a>
  <a href="{{ route('enseignant.emplois-temps') }}" class="sidebar-item active"><i class="fas fa-calendar-alt"></i> <span>Emplois de temps</span></a>
  
  <!-- Menu déroulant pour Non disponibilités -->
  <a href="#non-dispo" class="sidebar-item" data-bs-toggle="collapse" aria-expanded="false">
    <i class="fas fa-book"></i> <span>Non disponibilités</span>
    <i class="fas fa-chevron-down ms-auto"></i>
  </a>
  
  <div class="collapse" id="non-dispo">
    <a href="{{ route('enseignant.declaration') }}" class="sidebar-item sidebar-submenu">
      <i class="fas fa-pen"></i> <span>Declaration</span>
    </a>
    <a href="{{ route('enseignant.historique') }}" class="sidebar-item sidebar-submenu">
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
    <h4>Emploi du temps de <span>{{ $user->name }}</span> </h4>
  </div>

  <!-- Emploi du temps -->
  <div class="emploi-container">
    <div class="emploi-header">
      <h2 class="emploi-title">Mon Emploi du Temps</h2>
      <a href="{{ route('enseignant.emplois-temps.download', ['semestre' => $selectedSemestre]) }}" class="btn-download">
        <i class="fas fa-download"></i> Télécharger en PDF
      </a>
    </div>

    <!-- Sélecteur de semestre -->
    <form method="GET" action="{{ route('enseignant.emplois-temps') }}" class="semestre-selector">
      <label for="semestre">Sélectionner un semestre :</label>
      <select name="semestre" id="semestre" onchange="this.form.submit()">
        @foreach($semestres as $semestre)
          <option value="{{ $semestre->id_semestre }}" {{ $selectedSemestre == $semestre->id_semestre ? 'selected' : '' }}>
            {{ $semestre->nom_semestre }}
          </option>
        @endforeach
      </select>
    </form>

    <div class="table-responsive">
<table class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>Jour / Horaire</th>
            <th>8h30 - 10h30</th>
            <th>10h30 - 12h30</th>
            <th>14h30 - 16h30</th>
            <th>16h30 - 18h30</th>
        </tr>
    </thead>
    <tbody>
        @php
            $horaires = [
                '08:30:00-10:30:00' => '8h30 - 10h30',
                '10:30:00-12:30:00' => '10h30 - 12h30',
                '14:30:00-16:30:00' => '14h30 - 16h30',
                '16:30:00-18:30:00' => '16h30 - 18h30'
            ];
        @endphp

        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
            <tr>
                <td><strong>{{ $jour }}</strong></td>
                @foreach($horaires as $horaireKey => $horaireAffichage)
                    <td>
                        @if(isset($emploisParJour[$jour]) && $emploisParJour[$jour]->count() > 0)
                            @php
                                $seanceTrouvee = false;
                            @endphp
                            @foreach($emploisParJour[$jour] as $seance)
                                @php
                                    $seanceHoraire = $seance->debut . '-' . $seance->fin;
                                @endphp
                                @if($seanceHoraire === $horaireKey)
                                    @php $seanceTrouvee = true; @endphp
                                    <span class="badge bg-info text-dark mb-1">{{ $seance->type_seance }}</span><br>
                                    <strong>{{ $seance->module->nom_module ?? 'Module non défini' }}</strong><br>
                                    <small>Filière : {{ $seance->filiere->nom_filiere ?? 'Non définie' }}</small><br>
                                    <small>Salle : {{ $seance->salle->nom_salle ?? 'Non définie' }}</small><br>
                                    <small>Groupe : {{ $seance->groupe->nom_groupe ?? 'Non défini' }}</small>
                                @endif
                            @endforeach

                            @if(!$seanceTrouvee)
                                <span class="text-muted">-</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
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