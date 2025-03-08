@extends('layouts.app')

@section('title', 'Benutzerdetails')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Benutzerdetails</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Bearbeiten
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Löschen
                </button>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Zurück zur Liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i> Persönliche Informationen
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Benutzer-ID</label>
                        <div class="form-control-plaintext">{{ $user->id }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <div class="form-control-plaintext">{{ $user->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Vorname</label>
                        <div class="form-control-plaintext">{{ $user->vorname }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">E-Mail-Adresse</label>
                        <div class="form-control-plaintext">{{ $user->email }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Registriert am</label>
                        <div class="form-control-plaintext">{{ $user->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Letzte Aktualisierung</label>
                        <div class="form-control-plaintext">{{ $user->updated_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-tag me-2"></i> Rollen
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="list-group">
                            @foreach($user->roles as $role)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $role->name }}</h6>
                                        <p class="mb-1 text-muted small">{{ $role->description }}</p>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Diesem Benutzer sind keine Rollen zugewiesen.
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-puzzle-piece me-2"></i> Zugängliche Module
                </div>
                <div class="card-body">
                    @php
                        $modules = App\Models\Module::getModulesForUser($user);
                    @endphp
                    
                    @if($modules->count() > 0)
                        <div class="list-group">
                            @foreach($modules as $module)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas {{ $module->icon }} me-2"></i>
                                            {{ $module->name }}
                                        </h6>
                                        <p class="mb-1 text-muted small">{{ $module->description }}</p>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $module->getPermissionLevelForUser($user) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Dieser Benutzer hat keinen Zugriff auf Module.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Benutzer löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sind Sie sicher, dass Sie den Benutzer <strong>{{ $user->vorname }} {{ $user->name }}</strong> löschen möchten?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
