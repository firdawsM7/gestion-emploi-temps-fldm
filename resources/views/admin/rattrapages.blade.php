<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Gestion des Rattrapages - Admin</title>
  
  <!-- Styles spécifiques à cette page -->
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

    /* Table Styles */
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .card-header {
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      border-radius: 15px 15px 0 0 !important;
      padding: 15px 20px;
    }

    .table-responsive {
      border-radius: 0 0 15px 15px;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      color: var(--text-dark);
      background-color: #f8f9fc;
    }

    .badge {
      padding: 8px 12px;
      border-radius: 20px;
      font-weight: 500;
    }

    .badge-en-attente {
      background-color: rgba(246, 194, 62, 0.2);
      color: var(--warning-color);
    }

    .badge-approuve {
      background-color: rgba(28, 200, 138, 0.2);
      color: var(--success-color);
    }

    .badge-rejete {
      background-color: rgba(231, 74, 59, 0.2);
      color: var(--danger-color);
    }

    /* Modal Styles */
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
      border-radius: 15px 15px 0 0;
      color: white;
    }
  </style>
</head>
<body>
  <!-- On étend le template master -->
  @extends('layouts.master')

  @section('main')
  <!-- Messages de notification -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Gestion des rattrapages -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h5 class="m-0 font-weight-bold text-white">Gestion des Demandes de Rattrapage</h5>
    </div>
    <div class="card-body">
      <!-- Tableau des demandes de rattrapage -->
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">Enseignant</th>
              <th scope="col">Date</th>
              <th scope="col">Période</th>
              <th scope="col">Type</th>
              <th scope="col">Module</th>
              <th scope="col">Groupe</th>
              <th scope="col">Date de demande</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rattrapages as $rattrapage)
              <tr>
                <td>{{ $rattrapage->user->name }}</td>
                <td>{{ $rattrapage->date->format('d/m/Y') }}</td>
                <td>{{ $rattrapage->periode }}</td>
                <td>{{ $rattrapage->type_seance }}</td>
                <td>{{ $rattrapage->module }}</td>
                <td>{{ $rattrapage->groupe }}</td>
                <td>{{ $rattrapage->created_at->format('d/m/Y H:i') }}</td>
                <td>
                  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#rattrapageModal{{ $rattrapage->id }}">
                    <i class="fas fa-cog"></i> Traiter
                  </button>
                </td>
              </tr>
              
              <!-- Modal pour traiter la demande -->
              <div class="modal fade" id="rattrapageModal{{ $rattrapage->id }}" tabindex="-1" aria-labelledby="rattrapageModalLabel{{ $rattrapage->id }}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="rattrapageModalLabel{{ $rattrapage->id }}">Traiter la demande de rattrapage</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.rattrapage.update', $rattrapage->id) }}" method="POST">
                      @csrf
                      @method('POST')
                      <div class="modal-body">
                        <div class="mb-3">
                          <p><strong>Enseignant:</strong> {{ $rattrapage->user->name }}</p>
                          <p><strong>Date:</strong> {{ $rattrapage->date->format('d/m/Y') }}</p>
                          <p><strong>Période:</strong> {{ $rattrapage->periode }}</p>
                          <p><strong>Type:</strong> {{ $rattrapage->type_seance }}</p>
                          <p><strong>Module:</strong> {{ $rattrapage->module }}</p>
                          <p><strong>Groupe:</strong> {{ $rattrapage->groupe }}</p>
                        </div>
                        
                        <div class="mb-3">
                          <label for="statut{{ $rattrapage->id }}" class="form-label">Statut</label>
                          <select class="form-select" id="statut{{ $rattrapage->id }}" name="statut" required>
                            <option value="en_attente" {{ $rattrapage->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="approuve" {{ $rattrapage->statut == 'approuve' ? 'selected' : '' }}>Approuver</option>
                            <option value="rejete" {{ $rattrapage->statut == 'rejete' ? 'selected' : '' }}>Rejeter</option>
                          </select>
                        </div>
                        
                        <div id="salleContainer{{ $rattrapage->id }}" class="mb-3" style="display: none;">
                          <label for="salle_attribuee{{ $rattrapage->id }}" class="form-label">Salle attribuée</label>
                          <select class="form-select" id="salle_attribuee{{ $rattrapage->id }}" name="salle_attribuee">
                            <option value="">Sélectionner une salle</option>
                            @foreach($salles as $salle)
                              <option value="{{ $salle->id_salle }}" {{ $rattrapage->salle_attribuee == $salle->id_salle ? 'selected' : '' }}>
                                {{ $salle->nom_salle }} ({{ $salle->type ?? 'Salle' }})
                              </option>
                            @endforeach
                          </select>
                        </div>
                        
                        <div id="raisonContainer{{ $rattrapage->id }}" class="mb-3" style="display: none;">
                          <label for="raison_refus{{ $rattrapage->id }}" class="form-label">Raison du refus</label>
                          <textarea class="form-control" id="raison_refus{{ $rattrapage->id }}" name="raison_refus" rows="3" placeholder="Raison du refus...">{{ $rattrapage->raison_refus }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="8" class="text-center py-4">
                  <i class="fas fa-calendar-check fa-2x text-muted mb-2"></i>
                  <p class="text-muted">Aucune demande de rattrapage en attente</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endsection

  <!-- Scripts spécifiques à cette page -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Gestion des modals de rattrapage
      const rattrapageModals = document.querySelectorAll('[id^="rattrapageModal"]');
      rattrapageModals.forEach(modal => {
        const modalId = modal.id;
        const rattrapageId = modalId.replace('rattrapageModal', '');
        
        const statutSelect = document.getElementById('statut' + rattrapageId);
        const salleContainer = document.getElementById('salleContainer' + rattrapageId);
        const raisonContainer = document.getElementById('raisonContainer' + rattrapageId);
        const salleSelect = document.getElementById('salle_attribuee' + rattrapageId);
        const raisonTextarea = document.getElementById('raison_refus' + rattrapageId);
        
        if (statutSelect && salleContainer && raisonContainer) {
          // Initial state
          toggleFields(statutSelect.value, salleContainer, raisonContainer, salleSelect, raisonTextarea);
          
          // On change event
          statutSelect.addEventListener('change', function() {
            toggleFields(this.value, salleContainer, raisonContainer, salleSelect, raisonTextarea);
          });
        }
      });

      function toggleFields(statut, salleContainer, raisonContainer, salleSelect, raisonTextarea) {
        if (statut === 'approuve') {
          salleContainer.style.display = 'block';
          raisonContainer.style.display = 'none';
          salleSelect.setAttribute('required', 'required');
          raisonTextarea.removeAttribute('required');
        } else if (statut === 'rejete') {
          salleContainer.style.display = 'none';
          raisonContainer.style.display = 'block';
          salleSelect.removeAttribute('required');
          raisonTextarea.setAttribute('required', 'required');
        } else {
          salleContainer.style.display = 'none';
          raisonContainer.style.display = 'none';
          salleSelect.removeAttribute('required');
          raisonTextarea.removeAttribute('required');
        }
      }
    });
  </script>
</body>
</html>