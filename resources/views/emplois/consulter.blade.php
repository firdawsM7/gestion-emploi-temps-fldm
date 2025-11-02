@extends('layouts.master')
@section('main')

<div class="container py-4">
    <div class="card main-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Consulter l'emploi du temps</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('emplois.consulter') }}" id="searchForm">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <label for="cycle" class="form-label">Cycle</label>
                        <select class="form-select" id="cycle" name="cycle" required>
                            <option value="">Choisir un cycle</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ request('cycle') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->cycle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filiere" class="form-label">Filière</label>
                        <select class="form-select" id="filiere" name="filiere" required>
                            <option value="">Choisir une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_filiere }}" {{ request('filiere') == $filiere->id_filiere ? 'selected' : '' }}>
                                    {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="semestre" class="form-label">Semestre</label>
                        <select class="form-select" id="semestre" name="semestre" required>
                            <option value="">Choisir un semestre</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id_semestre }}" {{ request('semestre') == $semestre->id_semestre ? 'selected' : '' }}>
                                    {{ $semestre->nom_semestre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="groupe" class="form-label">Groupe</label>
                        <select class="form-select" id="groupe" name="groupe" required {{ !request('filiere') ? 'disabled' : '' }}>
                            <option value="">Choisir un groupe</option>
                            @if(request('filiere') && isset($groupes) && $groupes->count() > 0)
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe->id_groupe }}" {{ request('groupe') == $groupe->id_groupe ? 'selected' : '' }}>
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
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                </div>
            </form>

            @if(isset($seances) && $seances->count() > 0)
            <hr class="my-4">
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> 
                Emploi du temps trouvé pour le groupe <strong>{{ $groupes->firstWhere('id_groupe', $groupe_id)->nom_groupe ?? 'Inconnu' }}</strong>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Module</th>
                            <th>Type</th>
                            <th>Enseignant</th>
                            <th>Salle</th>
                            <th>Cycle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seances as $seance)
                            <tr>
                                <td class="fw-bold">{{ ucfirst($seance->jour) }}</td>
                                <td>{{ $seance->debut }} - {{ $seance->fin }}</td>
                                <td>{{ $seance->module->nom_module ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $seance->type_seance == 'Cours' ? 'primary' : ($seance->type_seance == 'TD' ? 'success' : 'warning') }}">
                                        {{ $seance->type_seance }}
                                    </span>
                                </td>
                                <td>{{ $seance->enseignant->name ?? 'N/A' }}</td>
                                <td>{{ $seance->salle->nom_salle ?? 'N/A' }}</td>
                                <td>{{ $seance->cycle->cycle ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @elseif(request('filiere') && request('groupe') && request('semestre') && request('cycle'))
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Aucun emploi du temps trouvé pour les critères sélectionnés.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
<style>
    :root {
        --primary-color:#3f37c9;
        --secondary-color:#3f37c9;
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
        border-bottom: 1px solid #e3e6f0;
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
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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
        color: #3f37c9;
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
        background-color: rgba(78, 115, 223, 0.05);
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
    
    .btn-success {
        background: linear-gradient(180deg, var(--success-color) 0%, #17a673 100%);
        border: none;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.2rem 0.75rem rgba(28, 200, 138, 0.4);
    }
    
    .alert-info {
        background: linear-gradient(90deg, rgba(78, 115, 223, 0.1) 0%, rgba(255, 255, 255, 0.5) 100%);
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filiereSelect = document.getElementById('filiere');
    const semestreSelect = document.getElementById('semestre');
    const groupeSelect = document.getElementById('groupe');
    const groupeLoading = document.getElementById('groupeLoading');
    const groupeError = document.getElementById('groupeError');

    // Fonction pour charger les groupes
    function chargerGroupes() {
        const filiereId = filiereSelect.value;
        const semestreId = semestreSelect.value;
        
        if (!filiereId) {
            groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
            groupeSelect.disabled = true;
            return;
        }

        groupeSelect.disabled = true;
        groupeLoading.classList.remove('d-none');
        groupeError.classList.add('d-none');

        // Utiliser l'API publique pour charger les groupes
        fetch(`/api/groupes?filiere_id=${filiereId}&semestre_id=${semestreId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                groupeSelect.innerHTML = '<option value="">Choisir un groupe</option>';
                
                if (data.length === 0) {
                    groupeSelect.innerHTML += '<option value="" disabled>Aucun groupe disponible</option>';
                } else {
                    data.forEach(groupe => {
                        groupeSelect.innerHTML += `<option value="${groupe.id_groupe}">${groupe.nom_groupe}</option>`;
                    });
                }
                
                groupeSelect.disabled = false;
                groupeLoading.classList.add('d-none');
            })
            .catch(error => {
                console.error('Erreur:', error);
                groupeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                groupeSelect.disabled = true;
                groupeLoading.classList.add('d-none');
                groupeError.classList.remove('d-none');
            });
    }

    // Écouter les changements de filière et semestre
    filiereSelect.addEventListener('change', chargerGroupes);
    semestreSelect.addEventListener('change', chargerGroupes);

    // Charger les groupes au chargement si une filière est déjà sélectionnée
    if (filiereSelect.value) {
        chargerGroupes();
    }
});
</script>
@endpush