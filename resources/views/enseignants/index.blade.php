@extends('layouts.master')
@section('main')
<div class="content">
    <div class="card border-0 custom-card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 py-3 custom-card-header">
            <div>
                <h4 class="mb-0 card-title">
                    <i class="fas fa-chalkboard-teacher me-2 card-icon"></i>Gestion des Enseignants
                </h4>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('enseignants.index') }}" method="GET" class="me-2">
                    <div class="input-group search-group">
                        <input type="text" name="search" class="form-control search-input" placeholder="Rechercher par nom..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary search-icon-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('enseignants.create') }}" class="btn btn-primary btn-sm px-3 py-2 custom-btn">
                    <i class="fas fa-plus-circle me-1"></i> Nouvel enseignant
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3 custom-alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="custom-thead">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enseignants as $enseignant)
                            <tr class="align-middle custom-table-row">
                                <td class="ps-4">{{ $enseignant->id }}</td>
                                <td>
                                    <strong>{{ $enseignant->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge py-2 px-3 custom-badge">
                                        {{ $enseignant->email }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('enseignants.edit', $enseignant->id) }}" 
                                       class="btn btn-sm px-3 py-2 me-2 edit-btn">
                                        <i class="fas fa-edit me-1"></i> Modifier
                                    </a>

                                    <!-- Bouton Supprimer -->
                                    <form action="{{ route('enseignants.destroy', $enseignant->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm px-3 py-2 delete-btn"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ?')">
                                            <i class="fas fa-trash-alt me-1"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h5 class="mt-3">Aucun enseignant enregistré</h5>
                                    <p class="text-muted">Commencez par ajouter un nouvel enseignant</p>
                                    <a href="{{ route('enseignants.create') }}" class="btn btn-primary mt-2 px-4 py-2">
                                        <i class="fas fa-plus-circle me-1"></i> Ajouter un enseignant
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles communs */
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
    
    .custom-btn {
        background-color: #5c6bc0;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .custom-btn:hover {
        background-color: #455a9d;
        transform: translateY(-2px);
    }
    
    .custom-alert {
        border-radius: 8px;
        background-color: #e8f5e9;
        color: #2e7d32;
        border: none;
    }
    
    .search-group {
        width: 220px;
    }
    
    .search-input {
        border-radius: 8px 0 0 8px; 
        border-color: #e0e0e0;
        font-size: 0.9rem;
    }
    
    .search-icon-btn {
        border-radius: 0 8px 8px 0; 
        background-color: #5c6bc0; 
        border: none; 
        padding: 0.375rem 0.75rem;
    }
    
    .search-icon-btn:hover {
        background-color: #455a9d;
    }
    
    .custom-thead {
        background-color: #f5f7fa;
    }
    
    .custom-thead th {
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
        background-color: #e8eaf6;
        color: #1a237e;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .edit-btn {
        background-color: #e3f2fd;
        color: #1565c0;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .edit-btn:hover {
        background-color: #bbdefb;
    }
    
    .delete-btn {
        background-color: #ffebee;
        color: #c62828;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .delete-btn:hover {
        background-color: #ffcdd2;
    }
    
    .empty-state {
        color: #5c6bc0;
    }
    
    .empty-icon {
        font-size: 5rem; 
        opacity: 0.3;
    }
    
    .empty-state h5 {
        font-weight: 600;
    }
</style>
@endsection