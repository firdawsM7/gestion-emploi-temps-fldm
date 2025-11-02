@extends('layouts.master')
@section('main')
<div class="content">
    <div class="card border-0 custom-card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 py-3 custom-card-header">
            <div>
                <h4 class="mb-0 card-title">
                    <i class="fas fa-door-open me-2 card-icon"></i>Gestion des Salles
                </h4>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('salles.index') }}" method="GET" class="me-2">
                    <div class="input-group custom-search">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher une salle..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('salles.create') }}" class="btn btn-primary btn-sm px-3 py-2 custom-btn">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle salle
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 custom-alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 custom-alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(request('search') && $salles->isEmpty())
            <div class="alert alert-info alert-dismissible fade show mb-4 custom-alert">
                <i class="fas fa-info-circle me-2"></i>Aucune salle ne correspond à votre recherche "{{ request('search') }}".
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="custom-table-header">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nom</th>
                            <th>Capacité</th>
                            <th>Disponibilité</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salles as $salle)
                        <tr class="align-middle custom-table-row">
                            <td class="ps-4">{{ $salle->id_salle }}</td>
                            <td>
                                <strong>{{ $salle->nom_salle }}</strong>
                                @if($salle->description)
                                <p class="text-muted mb-0 small">{{ Str::limit($salle->description, 40) }}</p>
                                @endif
                            </td>
                            <td>{{ $salle->capacite }} places</td>
                            <td>
                                <span class="badge py-2 px-3 custom-badge {{ $salle->disponibilite ? 'badge-available' : 'badge-unavailable' }}">
                                    {{ $salle->disponibilite ? 'Disponible' : 'Occupée' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('salles.edit', $salle->id_salle) }}" 
                                       class="btn btn-sm px-3 py-2 btn-edit">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>

                                    <form action="{{ route('salles.destroy', $salle->id_salle) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm px-3 py-2 btn-delete"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')">
                                            <i class="fas fa-trash-alt me-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <h5 class="mt-3">Aucune salle enregistrée</h5>
                                <p class="text-muted">Commencez par ajouter une nouvelle salle</p>
                                <a href="{{ route('salles.create') }}" class="btn btn-primary mt-2 px-4 py-2 custom-btn">
                                    <i class="fas fa-plus-circle me-1"></i> Ajouter une salle
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($salles->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $salles->links('pagination::simple-bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Styles harmonisés */
    .custom-card {
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        border-left: 4px solid #5c6bc0;
    }
    
    .custom-card-header {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
    }
    
    .card-title {
        color: #1a237e; 
        font-weight: 600;
    }
    
    .card-icon {
        color: #5c6bc0;
    }
    
    .custom-search {
        width: 220px;
    }
    
    .custom-search .form-control {
        border-radius: 8px 0 0 8px;
        font-size: 0.9rem;
        border-color: #e0e0e0;
    }
    
    .search-btn {
        border-radius: 0 8px 8px 0;
        background-color: #5c6bc0;
        border: none;
        padding: 0.375rem 0.75rem;
    }
    
    .custom-btn {
        background-color: #5c6bc0;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .custom-btn:hover {
        background-color: #3f51b5;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(92, 107, 192, 0.3);
    }
    
    .custom-alert {
        border-radius: 8px;
        border: none;
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .alert-danger {
        background-color: #ffebee;
        color: #c62828;
    }
    
    .alert-info {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .custom-table-header {
        background-color: #f5f7fa;
    }
    
    .custom-table-header th {
        font-weight: 600; 
        color: #1a237e;
    }
    
    .custom-table-row {
        transition: all 0.2s ease;
    }
    
    .custom-table-row:hover {
        background-color: #f8f9fa;
    }
    
    .custom-badge {
        border-radius: 6px;
        font-weight: 500;
    }
    
    .badge-available {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .badge-unavailable {
        background-color: #ffebee;
        color: #c62828;
    }
    
    .btn-edit {
        background-color: #e3f2fd;
        color: #1565c0;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-edit:hover {
        background-color: #bbdefb;
    }
    
    .btn-delete {
        background-color: #ffebee;
        color: #c62828;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-delete:hover {
        background-color: #ffcdd2;
    }
    
    .empty-state {
        color: #5c6bc0;
    }
    
    .empty-icon {
        font-size: 5rem; 
        opacity: 0.3;
    }
    
    /* Styles modernes pour la pagination */
    .pagination {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        padding-left: 0;
        list-style: none;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.9rem;
        color: #4a5568;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        text-decoration: none;
        background-color: white;
    }
    
    .pagination .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e0;
        color: #2d3748;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #5c6bc0;
        border-color: #5c6bc0;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(92, 107, 192, 0.3);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #a0aec0;
        background-color: #f8f9fa;
        border-color: #e2e8f0;
        pointer-events: none;
    }
</style>
@endsection