@extends('layouts.master')
@section('main')
<div class="container my-5">
    <h1 class="mb-4 text-center">Modifier une séance</h1>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Détails de la séance</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('emplois.update', $seance->id_seance) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type_seance" class="form-label">Type de séance</label>
                        <select class="form-select" id="type_seance" name="type_seance" required>
                            <option value="Cours" {{ $seance->type_seance == 'Cours' ? 'selected' : '' }}>Cours</option>
                            <option value="TD" {{ $seance->type_seance == 'TD' ? 'selected' : '' }}>TD</option>
                            <option value="TP" {{ $seance->type_seance == 'TP' ? 'selected' : '' }}>TP</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_salle" class="form-label">Salle</label>
                        <select class="form-select" id="id_salle" name="id_salle" required>
                            <option value="">Choisir une salle</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id_salle }}" {{ $seance->id_salle == $salle->id_salle ? 'selected' : '' }}>
                                    {{ $salle->nom_salle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">Enseignant</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Choisir un enseignant</option>
                            @foreach($enseignants as $enseignant)
                                <option value="{{ $enseignant->id }}" {{ $seance->user_id == $enseignant->id ? 'selected' : '' }}>
                                    {{ $enseignant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_module" class="form-label">Module</label>
                        <select class="form-select" id="id_module" name="id_module" required>
                            <option value="">Choisir un module</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id_module }}" {{ $seance->id_module == $module->id_module ? 'selected' : '' }}>
                                    {{ $module->nom_module }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_cycle" class="form-label">Cycle</label>
                        <select class="form-select" id="id_cycle" name="id_cycle" required>
                            <option value="">Choisir un cycle</option>
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ $seance->id_cycle == $cycle->id ? 'selected' : '' }}>
                                    {{ $cycle->cycle }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jour</label>
                        <input type="text" class="form-control" value="{{ ucfirst($seance->jour) }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heure de début</label>
                        <input type="text" class="form-control" value="{{ $seance->debut }}" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Heure de fin</label>
                        <input type="text" class="form-control" value="{{ $seance->fin }}" readonly>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('emplois.index', [
                        'filiere_id' => $seance->id_filiere,
                        'groupe_id' => $seance->id_groupe,
                        'semestre_id' => $seance->id_semestre,
                        'cycle_id' => $seance->id_cycle
                    ]) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection