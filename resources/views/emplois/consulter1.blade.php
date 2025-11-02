<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps - Université</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #0C2340;
            --primary-light: #1a3e72;
            --secondary-color: #8C1515;
            --accent-color: #4E2A84;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 1rem 0;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1.5rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-size: 2rem;
            color: white;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius-sm);
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            padding: 1rem;
            color: white;
        }

        .card-header h2 {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .form-select {
            width: 100%;
            padding: 0.6rem;
            border-radius: var(--border-radius-sm);
            border: 1px solid #ced4da;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 35, 64, 0.25);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }

        .col {
            flex: 1;
            padding: 0 0.5rem;
            min-width: 200px;
            margin-bottom: 0.8rem;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .timetable-container {
            margin-top: 1.5rem;
        }

        .timetable-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .timetable-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .table-responsive {
            overflow-x: auto;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 0.85rem;
        }

        .timetable th, .timetable td {
            padding: 0.6rem;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }

        .timetable thead th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border: 1px solid var(--primary-light);
            font-size: 0.9rem;
        }

        .timetable tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .timetable tbody tr:hover {
            background-color: #e9ecef;
        }

        .timetable td:first-child {
            font-weight: 600;
            background-color: #f1f3f5;
        }

        .time-slot {
            min-width: 150px;
            min-height: 90px;
            vertical-align: top;
        }

        .session {
            padding: 0.4rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 0.2rem;
            font-size: 0.8rem;
        }

        .session-type {
            display: inline-block;
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .td-type {
            background-color: #e3f2fd;
            color: #0d47a1;
        }

        .tp-type {
            background-color: #e8f5e9;
            color: #1b5e20;
        }

        .cours-type {
            background-color: #fce4ec;
            color: #880e4f;
        }

        .session-details {
            text-align: left;
        }

        .session-title {
            font-weight: 600;
            margin-bottom: 0.2rem;
            font-size: 0.8rem;
        }

        .session-info {
            font-size: 0.75rem;
            color: #495057;
        }

        .empty-slot {
            color: #6c757d;
            font-style: italic;
            font-size: 0.8rem;
        }

        .alert {
            padding: 0.8rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .loading {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin-right: 0.4rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 0.8rem;
                text-align: center;
            }
            
            .col {
                flex: 100%;
            }
            
            .timetable-header {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            .timetable th, .timetable td {
                padding: 0.4rem;
            }
            
            .session {
                font-size: 0.75rem;
            }
            
            .container {
                padding: 0.8rem;
            }
        }

        /* Styles spécifiques pour le PDF */
        .pdf-mode .timetable-container {
            margin: 0;
            padding: 0;
        }

        .pdf-mode .timetable-title {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .pdf-mode .timetable {
            width: 100%;
            page-break-inside: avoid;
        }

        .pdf-mode .time-slot {
            min-height: 80px;
        }
        
        /* Styles pour réduire la hauteur du cadre de consultation */
        .compact-form .card-body {
            padding: 1rem;
        }
        
        .compact-form .form-group {
            margin-bottom: 0.7rem;
        }
        
        .compact-form .form-select {
            padding: 0.5rem;
        }
        
        .compact-form .btn {
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo-container">
                    <i class="fas fa-graduation-cap logo"></i>
                    <h1>Université Sidi Mohamed Ben Abdellah Fes</h1>
                </div>
                <a href="{{ route('login') }}" class="btn btn-success">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
            </div>
        </header>

        <div class="card compact-form">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> Consulter les emplois du temps</h2>
            </div>
            <div class="card-body">
                <form id="timetable-form" action="{{ route('emplois.rechercher1') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="filiere" class="form-label">Filière</label>
                                <select class="form-select" id="filiere" name="filiere" required>
                                    <option value="">Choisir une filière</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id_filiere }}" {{ old('filiere', $filiere_id ?? '') == $filiere->id_filiere ? 'selected' : '' }}>
                                            {{ $filiere->nom_filiere }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="cycle" class="form-label">Cycle</label>
                                <select class="form-select" id="cycle" name="cycle" required>
                                    <option value="">Choisir un cycle</option>
                                    @foreach($cycles as $cycle)
                                        <option value="{{ $cycle->id }}" {{ old('cycle', $cycle_id ?? '') == $cycle->id ? 'selected' : '' }}>
                                            {{ $cycle->cycle }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="semestre" class="form-label">Semestre</label>
                                <select class="form-select" id="semestre" name="semestre" required>
                                    <option value="">Choisir un semestre</option>
                                    @foreach($semestres as $semestre)
                                        <option value="{{ $semestre->id_semestre }}" {{ old('semestre', $semestre_id ?? '') == $semestre->id_semestre ? 'selected' : '' }}>
                                            {{ $semestre->nom_semestre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="groupe" class="form-label">Groupe</label>
                                <select class="form-select" id="groupe" name="groupe" required>
                                    <option value="">Choisir un groupe</option>
                                    @if(isset($filiere_id) && $filiere_id)
                                        @foreach($groupes as $groupe)
                                            <option value="{{ $groupe->id_groupe }}" {{ old('groupe', $groupe_id ?? '') == $groupe->id_groupe ? 'selected' : '' }}>
                                                {{ $groupe->nom_groupe }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Consulter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($seances) && $seances->isNotEmpty() && isset($filiere_id) && isset($groupe_id) && isset($semestre_id) && isset($cycle_id))
        <div class="timetable-container">
            <div class="timetable-header">
                <h2 class="timetable-title">
                    Emploi du temps : 
                    @php
                        $filiere = $filieres->where('id_filiere', $filiere_id)->first();
                        $groupe = $groupes->where('id_groupe', $groupe_id)->first();
                        $semestre = $semestres->where('id_semestre', $semestre_id)->first();
                        $cycle = $cycles->where('id', $cycle_id)->first();
                    @endphp
                    {{ $filiere->nom_filiere ?? 'Inconnue' }} - 
                    {{ $groupe->nom_groupe ?? 'Inconnu' }} - 
                    {{ $semestre->nom_semestre ?? 'Inconnu' }} - 
                    {{ $cycle->cycle ?? 'Inconnu' }}
                </h2>
                <button id="export-pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exporter en PDF
                </button>
            </div>

            <div id="timetable-content">
                <div class="table-responsive">
                    <table class="timetable">
                        <thead>
                            <tr>
                                <th>Jours / Horaires</th>
                                <th>08:30 - 10:30</th>
                                <th>10:30 - 12:30</th>
                                <th>14:30 - 16:30</th>
                                <th>16:30 - 18:30</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                                $timeSlots = [
                                    '08:30:00-10:30:00',
                                    '10:30:00-12:30:00', 
                                    '14:30:00-16:30:00',
                                    '16:30:00-18:30:00',
                                ];
                            @endphp

                            @foreach ($days as $day)
                                <tr>
                                    <td><strong>{{ $day }}</strong></td>
                                    @foreach ($timeSlots as $slot)
                                        @php
                                            [$start, $end] = explode('-', $slot);
                                            $session = $seances->first(function($s) use ($day, $start, $end) {
                                                return strtolower($s->jour) === strtolower($day) &&
                                                       $s->debut === $start &&
                                                       $s->fin === $end;
                                            });
                                        @endphp
                                        <td class="time-slot">
                                            @if ($session)
                                                <div class="session">
                                                    <span class="session-type 
                                                        @if($session->type_seance == 'TD') td-type
                                                        @elseif($session->type_seance == 'TP') tp-type
                                                        @elseif($session->type_seance == 'Cours') cours-type
                                                        @endif">
                                                        {{ $session->type_seance }}
                                                    </span>
                                                    <div class="session-details">
                                                        <div class="session-title">{{ $session->module->nom_module ?? 'Module inconnu' }}</div>
                                                        <div class="session-info">Salle : {{ $session->salle->nom_salle ?? $session->id_salle }}</div>
                                                        <div class="session-info">Prof : {{ $session->enseignant->name ?? 'Inconnu' }}</div>
                                                        <div class="session-info">{{ $session->groupe->nom_groupe ?? 'Inconnu' }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="empty-slot">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @elseif(isset($seances) && $seances->isEmpty() && isset($filiere_id) && isset($groupe_id) && isset($semestre_id) && isset($cycle_id))
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Aucun emploi du temps trouvé pour cette sélection.
        </div>
        @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Veuillez sélectionner tous les critères pour consulter l'emploi du temps.
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('export-pdf');
            const filiereSelect = document.getElementById('filiere');
            const groupeSelect = document.getElementById('groupe');
            
            // Charger les groupes en fonction de la filière sélectionnée
            if (filiereSelect) {
                filiereSelect.addEventListener('change', function() {
                    const filiereId = this.value;
                    
                    if (filiereId) {
                        // Afficher un indicateur de chargement
                        groupeSelect.innerHTML = '<option value="">Chargement...</option>';
                        
                        fetch(`/api/groupes?filiere_id=${filiereId}`)
                            .then(response => response.json())
                            .then(data => {
                                groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
                                data.forEach(groupe => {
                                    const option = document.createElement('option');
                                    option.value = groupe.id_groupe;
                                    option.textContent = groupe.nom_groupe;
                                    groupeSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                console.error('Erreur lors du chargement des groupes:', error);
                                groupeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                            });
                    } else {
                        groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
                    }
                });
            }
            
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    // Afficher l'indicateur de chargement
                    exportBtn.innerHTML = '<span class="loading"></span> Génération du PDF...';
                    exportBtn.disabled = true;
                    
                    // Créer un clone du contenu pour l'export PDF
                    const content = document.getElementById('timetable-content').cloneNode(true);
                    const title = document.querySelector('.timetable-title').cloneNode(true);
                    
                    // Créer un conteneur pour le PDF
                    const pdfContainer = document.createElement('div');
                    pdfContainer.className = 'pdf-mode';
                    pdfContainer.style.padding = '20px';
                    pdfContainer.style.backgroundColor = 'white';
                    
                    // Ajouter le titre
                    const pdfTitle = title;
                    pdfTitle.style.textAlign = 'center';
                    pdfTitle.style.marginBottom = '20px';
                    pdfTitle.style.fontSize = '20px';
                    pdfTitle.style.color = '#0C2340';
                    pdfContainer.appendChild(pdfTitle);
                    
                    // Ajouter le tableau
                    pdfContainer.appendChild(content);
                    
                    // Options pour html2pdf
                    const opt = {
                        margin: 10,
                        filename: 'emploi_du_temps.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true },
                        jsPDF: { unit: 'mm', format: 'a3', orientation: 'landscape' }
                    };
                    
                    // Générer le PDF
                    html2pdf().set(opt).from(pdfContainer).save().then(() => {
                        // Restaurer le bouton après la génération
                        exportBtn.innerHTML = '<i class="fas fa-file-pdf"></i> Exporter en PDF';
                        exportBtn.disabled = false;
                    });
                });
            }

            // Vérification que tous les champs sont remplis avant soumission
            document.getElementById('timetable-form').addEventListener('submit', function(e) {
                const filiere = document.getElementById('filiere').value;
                const cycle = document.getElementById('cycle').value;
                const semestre = document.getElementById('semestre').value;
                const groupe = document.getElementById('groupe').value;
                
                if (!filiere || !cycle || !semestre || !groupe) {
                    e.preventDefault();
                    alert('Veuillez sélectionner tous les critères avant de consulter.');
                }
            });
        });
    </script>
</body>
</html>