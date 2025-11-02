@extends('layouts.master')
@section('main')
<div class="content">
    <div class="card border-0" style="
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        border-left: 4px solid #5c6bc0;
    ">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 py-3" style="
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        ">
            <div>
                <h4 class="mb-0" style="color: #1a237e; font-weight: 600;">
                    <i class="fas fa-graduation-cap me-2" style="color: #5c6bc0;"></i>Gestion des Filières
                </h4>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('filieres.index') }}" method="GET" class="me-2">
                    <div class="input-group" style="width: 220px;">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher une filière..." 
                               value="{{ request('search') }}" style="border-radius: 8px 0 0 8px; font-size: 0.9rem; border-color: #e0e0e0;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0; background-color: #5c6bc0; border: none; padding: 0.375rem 0.75rem;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('filieres.create') }}" class="btn btn-primary btn-sm px-3 py-2" style="
                    background-color: #5c6bc0;
                    border: none;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle filière
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" style="
                    border-radius: 8px;
                    background-color: #e8f5e9;
                    color: #2e7d32;
                    border: none;
                ">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" style="
                    border-radius: 8px;
                    background-color: #ffebee;
                    color: #c62828;
                    border: none;
                ">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f5f7fa;">
                        <tr>
                            <th class="ps-4" style="font-weight: 600; color: #1a237e;">ID</th>
                            <th style="font-weight: 600; color: #1a237e;">Nom</th>
                            <th style="font-weight: 600; color: #1a237e;">Département</th>
                            <th class="text-end pe-4" style="font-weight: 600; color: #1a237e;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filieres as $filiere)
                        <tr class="align-middle" style="transition: all 0.2s ease;">
                            <td class="ps-4">{{ $filiere->id_filiere }}</td>
                            <td>
                                <strong>{{ $filiere->nom_filiere }}</strong>
                            </td>
                            <td>
                                <span class="badge py-2 px-3" style="
                                    background-color: #e8eaf6;
                                    color: #1a237e;
                                    border-radius: 6px;
                                    font-weight: 500;
                                ">
                                    {{ $filiere->departement->nom ?? 'Non attribué' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('filieres.edit', $filiere->id_filiere) }}" class="btn btn-sm px-3 py-2 me-2" style="
                                    background-color: #e3f2fd;
                                    color: #1565c0;
                                    border-radius: 8px;
                                    font-weight: 500;
                                    transition: all 0.3s ease;
                                "
                                onmouseover="this.style.backgroundColor='#bbdefb'" 
                                onmouseout="this.style.backgroundColor='#e3f2fd'">
                                    <i class="fas fa-edit me-1"></i> Modifier
                                </a>
                                <form action="{{ route('filieres.destroy', $filiere->id_filiere) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm px-3 py-2" style="
                                        background-color: #ffebee;
                                        color: #c62828;
                                        border-radius: 8px;
                                        font-weight: 500;
                                        transition: all 0.3s ease;
                                    "
                                    onmouseover="this.style.backgroundColor='#ffcdd2'" 
                                    onmouseout="this.style.backgroundColor='#ffebee'"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?')">
                                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color: #5c6bc0;">
                                <div style="font-size: 5rem; opacity: 0.3;">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h5 class="mt-3" style="font-weight: 600;">Aucune filière enregistrée</h5>
                                <p class="text-muted">Commencez par ajouter une nouvelle filière</p>
                                <a href="{{ route('filieres.create') }}" class="btn btn-primary mt-2 px-4 py-2" style="
                                    background-color: #5c6bc0;
                                    border: none;
                                    border-radius: 8px;
                                    font-weight: 500;
                                ">
                                    <i class="fas fa-plus-circle me-1"></i> Ajouter une filière
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if (isset($filieres) && $filieres->hasPages())
                <div class="card-footer py-3 bg-white border-0">
                    <div class="d-flex justify-content-center">
                        {{ $filieres->links('pagination::simple-bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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