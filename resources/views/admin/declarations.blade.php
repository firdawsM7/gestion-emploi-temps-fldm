<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Déclarations - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 0.5rem 1.5rem rgba(22, 28, 45, 0.1);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 1rem 3rem rgba(22, 28, 45, 0.15);
        }
        
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            padding: 1.2rem 1.5rem;
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color)) !important;
        }
        
        .table-responsive {
            border-radius: 0 0 12px 12px;
        }
        
        table {
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }
        
        thead th {
            background-color: #f3f7ff;
            padding: 1rem 0.75rem;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            position: sticky;
            top: 0;
        }
        
        tbody tr {
            transition: all 0.2s ease;
        }
        
        tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
            transform: translateX(4px);
        }
        
        tbody td {
            padding: 1.1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f9;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
            border-radius: 6px;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s ease;
        }
        
        .btn-success {
            background: linear-gradient(to right, #2ecc71, #27ae60);
            border: none;
        }
        
        .btn-danger {
            background: linear-gradient(to right, #e74c3c, #c0392b);
            border: none;
        }
        
        .btn-group .btn {
            margin: 0 3px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state {
            padding: 3rem 1rem;
        }
        
        .empty-state i {
            font-size: 4rem;
            opacity: 0.7;
            margin-bottom: 1.5rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .period-badge {
            font-size: 0.75rem;
            margin-top: 4px;
        }
        
        /* Animation pour l'apparition des lignes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        tbody tr {
            animation: fadeIn 0.4s ease forwards;
        }
        
        tbody tr:nth-child(1) { animation-delay: 0.05s; }
        tbody tr:nth-child(2) { animation-delay: 0.1s; }
        tbody tr:nth-child(3) { animation-delay: 0.15s; }
        tbody tr:nth-child(4) { animation-delay: 0.2s; }
        tbody tr:nth-child(5) { animation-delay: 0.25s; }
        tbody tr:nth-child(n+6) { animation-delay: 0.3s; }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 1.2rem;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn-group .btn {
                margin: 0;
            }
            
            thead th:nth-child(3),
            td:nth-child(3) {
                display: none;
            }
        }
    </style>
</head>
<body>
    @extends('layouts.master')

    @section('main')
    <div class="container-fluid py-4">
        <!-- Messages de notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Gestion des Déclarations de Non-Disponibilité
                </h5>
                <span class="badge bg-light text-primary fs-6">
                    {{ $declarations->count() }} déclaration(s) en attente
                </span>
            </div>
            <div class="card-body p-0">
                @if($declarations->isEmpty())
                    <div class="text-center empty-state">
                        <i class="fas fa-check-circle text-success"></i>
                        <h4 class="text-muted mt-3">Aucune déclaration en attente</h4>
                        <p class="text-muted">Toutes les déclarations ont été traitées.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Période</th>
                                    <th>Type</th>
                                    <th>Raison</th>
                                    <th>Date de déclaration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($declarations as $declaration)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $declaration->enseignant->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $declaration->enseignant->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($declaration->date_debut->format('Y-m-d') === $declaration->date_fin->format('Y-m-d'))
                                                {{ $declaration->date_debut->format('d/m/Y') }}
                                            @else
                                                {{ $declaration->date_debut->format('d/m/Y') }} - {{ $declaration->date_fin->format('d/m/Y') }}
                                            @endif
                                            @if($declaration->periode)
                                                <br>
                                                <small class="text-muted badge bg-info period-badge">{{ $declaration->periode }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $declaration->type_periode === 'journee' ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $declaration->type_periode === 'journee' ? 'Journée complète' : 'Période spécifique' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $declaration->raison }}">
                                                {{ Str::limit($declaration->raison, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $declaration->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.declaration.update', $declaration->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="statut" value="approuve">
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir approuver cette déclaration ?')"
                                                            data-bs-toggle="tooltip" title="Approuver">
                                                        <i class="fas fa-check me-1"></i> Accepter
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.declaration.update', $declaration->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="statut" value="rejete">
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir rejeter cette déclaration ?')"
                                                            data-bs-toggle="tooltip" title="Rejeter">
                                                        <i class="fas fa-times me-1"></i> Rejeter
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Activer les tooltips Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
    @endsection
</body>
</html>