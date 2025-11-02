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
                    <i class="fas fa-sitemap me-2" style="color: #5c6bc0;"></i>Gestion des Départements
                </h4>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('departements.search') }}" method="GET" class="me-2">
                    <div class="input-group" style="width: 220px;">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un département..." 
                               value="{{ request('search') }}" style="border-radius: 8px 0 0 8px; font-size: 0.9rem; border-color: #e0e0e0;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0; background-color: #5c6bc0; border: none; padding: 0.375rem 0.75rem;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ route('departements.create') }}" class="btn btn-primary btn-sm px-3 py-2" style="
                    background-color: #5c6bc0;
                    border: none;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-plus-circle me-1"></i> Nouveau département
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

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f5f7fa;">
                        <tr>
                            <th class="ps-4" style="font-weight: 600; color: #1a237e;">ID</th>
                            <th style="font-weight: 600; color: #1a237e;">Nom</th>
                            <th style="font-weight: 600; color: #1a237e;">Responsable</th>
                            <th class="text-end pe-4" style="font-weight: 600; color: #1a237e;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departements as $departement)
                        <tr class="align-middle" style="transition: all 0.2s ease;">
                            <td class="ps-4">{{ $departement->id_departement }}</td>
                            <td>
                                <strong>{{ $departement->nom }}</strong>
                            </td>
                            <td>
                                <span class="badge py-2 px-3" style="
                                    background-color: #e8eaf6;
                                    color: #1a237e;
                                    border-radius: 6px;
                                    font-weight: 500;
                                ">
                                    {{ $departement->responsable ?? 'Aucun' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('departements.edit', $departement->id_departement) }}" class="btn btn-sm px-3 py-2 me-2" style="
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
                                <form action="{{ route('departements.destroy', $departement->id_departement) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm px-3 py-2" 
                                            style="
                                                background-color: #ffebee;
                                                color: #c62828;
                                                border-radius: 8px;
                                                font-weight: 500;
                                                transition: all 0.3s ease;
                                            "
                                            onmouseover="this.style.backgroundColor='#ffcdd2'" 
                                            onmouseout="this.style.backgroundColor='#ffebee'"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?')">
                                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color: #5c6bc0;">
                                <div style="font-size: 5rem; opacity: 0.3;">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <h5 class="mt-3" style="font-weight: 600;">Aucun département enregistré</h5>
                                <p class="text-muted">Commencez par ajouter un nouveau département</p>
                                <a href="{{ route('departements.create') }}" class="btn btn-primary mt-2 px-4 py-2" style="
                                    background-color: #5c6bc0;
                                    border: none;
                                    border-radius: 8px;
                                    font-weight: 500;
                                ">
                                    <i class="fas fa-plus-circle me-1"></i> Ajouter un département
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
@endsection