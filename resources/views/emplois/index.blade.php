@php
    use Carbon\Carbon;

    $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    
    // Format des plages cohérent avec la base de données
    $plages = [
        '08:30:00' => '08:30 - 10:30',
        '10:30:00' => '10:30 - 12:30', 
        '14:30:00' => '14:30 - 16:30',
        '16:30:00' => '16:30 - 18:30'
    ];

    // Reconstruire correctement la structure des données
    $emploisParJourPlage = [];
    foreach ($emplois as $emploi) {
        $emploisParJourPlage[$emploi->jour][$emploi->debut] = $emploi;
    }
@endphp

@extends('layouts.master')
@section('main')
<div class="container py-4">
    <div class="card main-card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Emploi du temps</h4>
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form method="GET" action="{{ route('emplois.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cycle_id" class="form-label">Cycle</label>
                        <select class="form-select" id="cycle_id" name="cycle_id">
                            <option value="">Tous les cycles</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->cycle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="filiere_id" class="form-label">Filière</label>
                        <select class="form-select" id="filiere_id" name="filiere_id">
                            <option value="">Toutes les filières</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id_filiere }}" {{ request('filiere_id') == $filiere->id_filiere ? 'selected' : '' }}>
                                    {{ $filiere->nom_filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="semestre_id" class="form-label">Semestre</label>
                        <select class="form-select" id="semestre_id" name="semestre_id">
                            <option value="">Tous les semestres</option>
                            @foreach($semestres as $semestre)
                                <option value="{{ $semestre->id_semestre }}" {{ request('semestre_id') == $semestre->id_semestre ? 'selected' : '' }}>
                                    {{ $semestre->nom_semestre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="groupe_id" class="form-label">Groupe</label>
                        <select class="form-select" id="groupe_id" name="groupe_id" {{ !request('filiere_id') ? 'disabled' : '' }}>
                            <option value="">Tous les groupes</option>
                            @if(request('filiere_id') && $groupes->count() > 0)
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe->id_groupe }}" {{ request('groupe_id') == $groupe->id_groupe ? 'selected' : '' }}>
                                        {{ $groupe->nom_groupe }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i> Filtrer
                    </button>
                    <a href="{{ route('emplois.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Réinitialiser
                    </a>
                </div>
            </form>

            @if(request('filiere_id') && request('groupe_id') && request('semestre_id') && request('cycle_id'))
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i> 
                    Emploi du temps pour : 
                    <strong>{{ $selectedGroupe->nom_groupe ?? 'Groupe inconnu' }}</strong> - 
                    Cycle <strong>{{ $cycles->firstWhere('id', request('cycle_id'))->cycle ?? 'Inconnu' }}</strong> - 
                    Semestre <strong>{{ $semestres->firstWhere('id_semestre', request('semestre_id'))->nom_semestre ?? 'Inconnu' }}</strong>
                    
                    <div class="mt-2">
                        <a href="{{ route('emplois.ajouter', [
                            'filiere_id' => request('filiere_id'),
                            'groupe_id' => request('groupe_id'),
                            'semestre_id' => request('semestre_id'),
                            'cycle_id' => request('cycle_id')
                        ]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-edit me-1"></i> Modifier cet emploi
                        </a>
                        
                        <form action="{{ route('emplois.exporter') }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <input type="hidden" name="filiere_id" value="{{ request('filiere_id') }}">
                            <input type="hidden" name="groupe_id" value="{{ request('groupe_id') }}">
                            <input type="hidden" name="semestre_id" value="{{ request('semestre_id') }}">
                            <input type="hidden" name="cycle_id" value="{{ request('cycle_id') }}">
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-file-excel me-1"></i> Exporter Excel
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Debug: Afficher les données pour vérification -->
            @if($emplois->count() > 0)
                <div class="alert alert-warning mb-3">
                    <strong>Debug:</strong> {{ $emplois->count() }} séance(s) trouvée(s)
                    @foreach($emplois as $emploi)
                        <br>- {{ $emploi->jour }} {{ $emploi->debut }}-{{ $emploi->fin }}: {{ $emploi->module->nom_module ?? 'N/A' }}
                    @endforeach
                </div>
            @endif

            <!-- Tableau des emplois du temps -->
            @if($emplois->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Jours</th>
                                @foreach($plages as $heureDebut => $plageAffichage)
                                    <th class="text-center">{{ $plageAffichage }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jours as $jour)
                                <tr>
                                    <td class="fw-bold" style="background-color: #f0f4ff; width: 120px;">{{ ucfirst($jour) }}</td>
                                    @foreach($plages as $heureDebut => $plageAffichage)
                                        @php
                                            $emploi = $emploisParJourPlage[$jour][$heureDebut] ?? null;
                                        @endphp
                                        <td style="min-width: 200px; vertical-align: top;">
                                            @if($emploi)
                                                <div class="seance-card">
                                                    <div class="seance-header">
                                                        <strong>{{ $emploi->module->nom_module ?? 'Module inconnu' }}</strong>
                                                    </div>
                                                    <div class="seance-details">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user-tie me-1"></i>{{ $emploi->enseignant->name ?? 'Non assigné' }}
                                                        </small>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-door-open me-1"></i>{{ $emploi->salle->nom_salle ?? 'Salle non définie' }}
                                                        </small>
                                                        <br>
                                                        <small class="badge bg-{{ $emploi->type_seance == 'Cours' ? 'primary' : ($emploi->type_seance == 'TD' ? 'success' : 'warning') }}">
                                                            {{ $emploi->type_seance }}
                                                        </small>
                                                    </div>
                                                    @if(Auth::check() && Auth::user()->id_role == 1)
                                                        <div class="seance-actions mt-2">
                                                            <a href="{{ route('emplois.edit', $emploi->id_seance) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('emplois.destroy', $emploi->id_seance) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                                    <br>
                                                    <small>Aucune séance</small>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucun emploi du temps trouvé</h4>
                    <p class="text-muted">
                        @if(request('filiere_id') || request('groupe_id') || request('semestre_id') || request('cycle_id'))
                            Aucune séance ne correspond aux critères de recherche sélectionnés.
                        @else
                            Aucun emploi du temps n'a été créé pour le moment.
                        @endif
                    </p>
                    <a href="{{ route('emplois.ajouter') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i> Créer un nouvel emploi du temps
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #150e96ff;
        --secondary-color: #110b82ff;
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
    
    .seance-card {
        background: white;
        border-radius: 0.5rem;
        padding: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-left: 4px solid var(--primary-color);
    }
    
    .seance-header {
        font-size: 0.9rem;
        font-weight: 600;
        color: #072c99ff;
        margin-bottom: 0.5rem;
    }
    
    .seance-details {
        font-size: 0.8rem;
    }
    
    .seance-actions {
        display: flex;
        gap: 0.25rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filiereSelect = document.getElementById('filiere_id');
    const groupeSelect = document.getElementById('groupe_id');

    function chargerGroupes() {
        const filiereId = filiereSelect.value;
        
        if (!filiereId) {
            groupeSelect.innerHTML = '<option value="">Tous les groupes</option>';
            groupeSelect.disabled = true;
            return;
        }

        fetch(`/api/groupes?filiere_id=${filiereId}`)
            .then(response => response.json())
            .then(data => {
                groupeSelect.innerHTML = '<option value="">Tous les groupes</option>';
                
                if (data.length > 0) {
                    data.forEach(groupe => {
                        const selected = groupe.id_groupe == '{{ request('groupe_id') }}' ? 'selected' : '';
                        groupeSelect.innerHTML += `<option value="${groupe.id_groupe}" ${selected}>${groupe.nom_groupe}</option>`;
                    });
                }
                
                groupeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erreur:', error);
                groupeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                groupeSelect.disabled = true;
            });
    }

    filiereSelect.addEventListener('change', chargerGroupes);

    if (filiereSelect.value) {
        chargerGroupes();
    }
});
</script>
@endsection