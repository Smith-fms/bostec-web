@extends('layouts.app')

@section('title', 'Moduldetails')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Moduldetails</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('modules.edit', $module) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Bearbeiten
                </a>
                <form action="{{ route('modules.toggle-active', $module) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $module->active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                        <i class="fas {{ $module->active ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i> 
                        {{ $module->active ? 'Deaktivieren' : 'Aktivieren' }}
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i> Löschen
                </button>
            </div>
            <a href="{{ route('modules.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Zurück zur Liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas {{ $module->icon }} me-2"></i> Modulinformationen
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Modul-ID</label>
                        <div class="form-control-plaintext">{{ $module->id }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <div class="form-control-plaintext">{{ $module->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Beschreibung</label>
                        <div class="form-control-plaintext">{{ $module->description ?: 'Keine Beschreibung vorhanden' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Route</label>
                        <div class="form-control-plaintext"><code>{{ $module->route }}</code></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Icon</label>
                        <div class="form-control-plaintext">
                            <i class="fas {{ $module->icon }} me-2"></i> {{ $module->icon }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Version</label>
                        <div class="form-control-plaintext">{{ $module->version }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Reihenfolge</label>
                        <div class="form-control-plaintext">{{ $module->order }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <div class="form-control-plaintext">
                            @if($module->active)
                                <span class="badge bg-success">Aktiv</span>
                            @else
                                <span class="badge bg-danger">Inaktiv</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Erstellt am</label>
                        <div class="form-control-plaintext">{{ $module->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Letzte Aktualisierung</label>
                        <div class="form-control-plaintext">{{ $module->updated_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-2"></i> Rollenberechtigungen
                </div>
                <div class="card-body">
                    @if($module->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rolle</th>
                                        <th>Beschreibung</th>
                                        <th>Berechtigung</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($module->roles as $role)
                                        <tr>
                                            <td>
                                                <a href="{{ route('roles.show', $role) }}" class="text-decoration-none">
                                                    {{ $role->name }}
                                                </a>
                                            </td>
                                            <td>{{ $role->description }}</td>
                                            <td>
                                                @if($role->pivot->permission_level == 'read')
                                                    <span class="badge bg-secondary">Lesen</span>
                                                @elseif($role->pivot->permission_level == 'write')
                                                    <span class="badge bg-primary">Schreiben</span>
                                                @elseif($role->pivot->permission_level == 'admin')
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
                            <i class="fas fa-exclamation-triangle me-2"></i> Dieses Modul hat keine Rollenberechtigungen.
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
                    <h5 class="modal-title" id="deleteModalLabel">Modul löschen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sind Sie sicher, dass Sie das Modul <strong>{{ $module->name }}</strong> löschen möchten?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Das Löschen eines Moduls entfernt auch alle zugehörigen Berechtigungen.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    <form action="{{ route('modules.destroy', $module) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Löschen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
