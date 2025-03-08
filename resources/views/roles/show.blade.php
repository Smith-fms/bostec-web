@extends('layouts.app')

@section('title', 'Rollendetails')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Rollendetails</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Bearbeiten
                </a>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" {{ $role->users->count() > 0 ? 'disabled' : '' }}>
                    <i class="fas fa-trash me-1"></i> Löschen
                </button>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Zurück zur Liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-tag me-2"></i> Rolleninformationen
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Rollen-ID</label>
                        <div class="form-control-plaintext">{{ $role->id }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <div class="form-control-plaintext">{{ $role->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Beschreibung</label>
                        <div class="form-control-plaintext">{{ $role->description ?: 'Keine Beschreibung vorhanden' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Erstellt am</label>
                        <div class="form-control-plaintext">{{ $role->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Letzte Aktualisierung</label>
                        <div class="form-control-plaintext">{{ $role->updated_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users me-2"></i> Zugewiesene Benutzer
                </div>
                <div class="card-body">
                    @if($role->users->count() > 0)
                        <div class="list-group">
                            @foreach($role->users as $user)
                                <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $user->vorname }} {{ $user->name }}</h6>
                                        <p class="mb-1 text-muted small">{{ $user->email }}</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Diese Rolle ist keinem Benutzer zugewiesen.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-puzzle-piece me-2"></i> Modulberechtigungen
                </div>
                <div class="card-body">
                    @if($role->modules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Modul</th>
                                        <th>Beschreibung</th>
                                        <th>Berechtigung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->modules as $module)
                                        <tr>
                                            <td>
                                                <i class="fas {{ $module->icon }} me-2"></i>
                                                {{ $module->name }}
                                            </td>
                                            <td>{{ $module->description }}</td>
                                            <td>
                                                @if($module->pivot->permission_level == 'read')
                                                    <span class="badge bg-secondary">Lesen</span>
                                                @elseif($module->pivot->permission_level == 'write')
                                                    <span class="badge bg-primary">Schreiben</span>
                                                @elseif($module->pivot->permission_level == 'admin')
                                                    <span class="badge bg-danger">Admin</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Diese Rolle hat keine Modulberechtigungen.
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
                    <h5 class="modal-title" id="deleteModalLabel">Rolle löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($role->users->count() > 0)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i> Diese Rolle ist noch {{ $role->users->count() }} Benutzer(n) zugewiesen und kann nicht gelöscht werden.
                        </div>
                        <p>Bitte entfernen Sie zuerst die Rolle von allen Benutzern.</p>
                    @else
                        <p>Sind Sie sicher, dass Sie die Rolle <strong>{{ $role->name }}</strong> löschen möchten?</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    @if($role->users->count() == 0)
                        <form action="{{ route('roles.destroy', $role) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Löschen</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
