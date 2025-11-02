@extends('layouts.master')
@section('main')
<div class="container py-4">
    <div class="card main-card">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-plus me-2"></i> Ajouter un nouvel emploi du temps</h4>
        </div>
        <div class="card-body">
            <div class="info-badge">
                <i class="fas fa-info-circle"></i> Sélectionnez d'abord le cycle, la filière, le semestre et le groupe
            </div>
            
            <form method="GET" action="{{ route('emplois.ajouter') }}" id="selectionForm">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <label for="cycle_id" class="form-label">Cycle</label>
                        <select class="form-select" id="cycle_id" name="cycle_id" required>
                            <option value="">Choisir un cycle</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->cycle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filiere_id" class="form-label">Filière</label>
                        <select class="form-select" id="filiere_id" name="filiere_id" required>
                            <option value="">Choisir une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_filiere }}" {{ request('filiere_id') == $filiere->id_filiere ? 'selected' : '' }}>
                                    {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="semestre_id" class="form-label">Semestre</label>
                        <select class="form-select" id="semestre_id" name="semestre_id" required>
                            <option value="">Choisir un semestre</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id_semestre }}" {{ request('semestre_id') == $semestre->id_semestre ? 'selected' : '' }}>
                                    {{ $semestre->nom_semestre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="groupe_id" class="form-label">Groupe</label>
                        <select class="form-select" id="groupe_id" name="groupe_id" required {{ !request('filiere_id') ? 'disabled' : '' }}>
                            <option value="">Choisir un groupe</option>
                            @if(request('filiere_id') && isset($groupes) && $groupes->count() > 0)
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe->id_groupe }}" {{ request('groupe_id') == $groupe->id_groupe ? 'selected' : '' }}>
                                        {{ $groupe->nom_groupe }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div id="groupeLoading" class="d-none mt-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <small class="text-muted ms-2">Chargement des groupes...</small>
                        </div>
                        <div id="groupeError" class="d-none mt-2">
                            <small class="text-danger">Erreur de chargement des groupes</small>
                        </div>
                        @if(request('filiere_id') && isset($groupes) && $groupes->count() == 0)
                            <small class="text-danger">Aucun groupe disponible pour cette filière</small>
                        @endif
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-dark" id="submitBtn">
                        <i class="fas fa-search me-1"></i> Charger l'emploi du temps
                    </button>
                </div>
            </form>

            @if(request('filiere_id') && request('groupe_id') && request('semestre_id') && request('cycle_id'))
            <hr class="my-4">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> 
                Vous éditez l'emploi du temps pour : 
                <strong>{{ $groupe->nom_groupe ?? 'Groupe inconnu' }}</strong> - 
                Cycle <strong>{{ $cycles->firstWhere('id', request('cycle_id'))->cycle ?? 'Inconnu' }}</strong> - 
                Semestre <strong>{{ $semestres->firstWhere('id_semestre', request('semestre_id'))->nom_semestre ?? 'Inconnu' }}</strong>
            </div>

            @if(isset($modules) && $modules->count() > 0)
            <form action="{{ route('emplois.store') }}" method="POST" id="emploiForm">
                @csrf
                <input type="hidden" name="filiere_id" value="{{ request('filiere_id') }}">
                <input type="hidden" name="groupe_id" value="{{ request('groupe_id') }}">
                <input type="hidden" name="semestre_id" value="{{ request('semestre_id') }}">
                <input type="hidden" name="cycle_id" value="{{ request('cycle_id') }}">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Jours</th>
                                <th>8h30-10h30</th>
                                <th>10h30-12h30</th>
                                <th>14h30-16h30</th>
                                <th>16h30-18h30</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi','samedi'] as $jour)
                            <tr>
                                <td class="fw-bold" style="background-color: #f0f4ff;">{{ ucfirst($jour) }}</td>
                                @foreach(['08:30 - 10:30', '10:30 - 12:30', '14:30 - 16:30', '16:30 - 18:30'] as $plage)
                                @php
                                    $seanceExistante = null;
                                    if (isset($emploiExistants[$jour]) && isset($emploiExistants[$jour][$plage])) {
                                        $seanceExistante = $emploiExistants[$jour][$plage]->first();
                                    }
                                @endphp
                                <td>
                                    <div class="seance-form">
                                        <select name="seances[{{ $jour }}][{{ $plage }}][module_id]" class="form-select form-select-sm mb-1">
                                            <option value="">Module</option>
                                            @foreach($modules as $module)
                                                <option value="{{ $module->id_module }}"
                                                    {{ $seanceExistante && $seanceExistante->id_module == $module->id_module ? 'selected' : '' }}>
                                                    {{ $module->nom_module }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="seances[{{ $jour }}][{{ $plage }}][salle_id]" class="form-select form-select-sm mb-1">
                                            <option value="">Salle</option>
                                            @foreach($salles as $salle)
                                                <option value="{{ $salle->id_salle }}"
                                                    {{ $seanceExistante && $seanceExistante->id_salle == $salle->id_salle ? 'selected' : '' }}>
                                                    {{ $salle->nom_salle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="seances[{{ $jour }}][{{ $plage }}][type_seance]" class="form-select form-select-sm mb-1">
                                            <option value="Cours" {{ $seanceExistante && $seanceExistante->type_seance == 'Cours' ? 'selected' : '' }}>Cours</option>
                                            <option value="TD" {{ $seanceExistante && $seanceExistante->type_seance == 'TD' ? 'selected' : '' }}>TD</option>
                                            <option value="TP" {{ $seanceExistante && $seanceExistante->type_seance == 'TP' ? 'selected' : '' }}>TP</option>
                                        </select>
                                        <select name="seances[{{ $jour }}][{{ $plage }}][user_id]" class="form-select form-select-sm">
                                            <option value="">Enseignant</option>
                                            @foreach($enseignants as $enseignant)
                                                <option value="{{ $enseignant->id }}"
                                                    {{ $seanceExistante && $seanceExistante->user_id == $enseignant->id ? 'selected' : '' }}>
                                                    {{ $enseignant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                    <a href="{{ route('emplois.index') }}" class="btn btn-secondary px-4 ms-2">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    Aucun module disponible pour cette filière et ce semestre.
                </div>
            @endif
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #1a237e;
        --secondary-color: #283593;
        --success-color: #1cc88a;
        --light-bg: #f8f9fc;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .main-card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid #0d1440;
        padding: 1.2rem 1.35rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }
    
    .form-select {
        border-radius: 0.5rem;
        padding: 0.6rem 1rem;
        border: 1px solid #d1d3e2;
        transition: all 0.2s;
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
    }
    
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
        margin: 2rem 0;
    }
    
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table th {
        background: linear-gradient(180deg, #f8f9fc 0%, #e3e6f0 100%);
        color: #1a237e;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 0.75rem;
        border: 1px solid #e3e6f0;
    }
    
    .table td {
        padding: 0.75rem;
        border: 1px solid #e3e6f0;
        vertical-align: top;
    }
    
    .table tr:nth-child(even) {
        background-color: #f8f9fc;
    }
    
    .table tr:hover {
        background-color: rgba(26, 35, 126, 0.05);
    }
    
    .seance-form {
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .seance-form select {
        margin-bottom: 0.5rem;
        transition: all 0.2s;
    }
    
    .seance-form select:focus {
        transform: translateY(-1px);
        box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    
    .btn {
        border-radius: 0.5rem;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .btn-dark {
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
    }
    
    .btn-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.2rem 0.75rem rgba(26, 35, 126, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(180deg, var(--success-color) 0%, #17a673 100%);
        border: none;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.2rem 0.75rem rgba(28, 200, 138, 0.4);
    }
    
    .alert-info {
        background: linear-gradient(90deg, rgba(26, 35, 126, 0.1) 0%, rgba(255, 255, 255, 0.5) 100%);
        border-left: 4px solid var(--primary-color);
        border-radius: 0.5rem;
        color: #5a5c69;
    }
    
    .info-badge {
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .info-badge i {
        margin-right: 0.5rem;
    }
    
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        .seance-form {
            min-width: 200px;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filiereSelect = document.getElementById('filiere_id');
    const groupeSelect = document.getElementById('groupe_id');
    const semestreSelect = document.getElementById('semestre_id');
    const cycleSelect = document.getElementById('cycle_id');

    function chargerGroupes() {
        const filiereId = filiereSelect.value;
        const semestreId = semestreSelect.value;
        
        if (!filiereId) {
            groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
            groupeSelect.disabled = true;
            return;
        }

        groupeSelect.disabled = true;

        fetch(`/api/groupes?filiere_id=${filiereId}&semestre_id=${semestreId}`)
            .then(response => response.json())
            .then(data => {
                groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
                
                if (data.length > 0) {
                    data.forEach(groupe => {
                        const selected = groupe.id_groupe == '{{ request('groupe_id') }}' ? 'selected' : '';
                        groupeSelect.innerHTML += `<option value="${groupe.id_groupe}" ${selected}>${groupe.nom_groupe}</option>`;
                    });
                } else {
                    groupeSelect.innerHTML += '<option value="" disabled>Aucun groupe disponible</option>';
                }
                
                groupeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erreur:', error);
                groupeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                groupeSelect.disabled = true;
            });
    }

    // Événements
    filiereSelect.addEventListener('change', function() {
        chargerGroupes();
        document.getElementById('selectionForm').submit();
    });

    semestreSelect.addEventListener('change', function() {
        chargerGroupes();
        document.getElementById('selectionForm').submit();
    });

    cycleSelect.addEventListener('change', function() {
        document.getElementById('selectionForm').submit();
    });

    groupeSelect.addEventListener('change', function() {
        document.getElementById('selectionForm').submit();
    });

    // Charger au démarrage
    if (filiereSelect.value) {
        chargerGroupes();
    }
});
</script>
@endsection