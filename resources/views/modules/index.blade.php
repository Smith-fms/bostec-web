@extends('layouts.app')

@section('title', 'Modulverwaltung')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Modulverwaltung</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('modules.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Neues Modul
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Beschreibung</th>
                            <th>Route</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Reihenfolge</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                            <tr>
                                <td>{{ $module->id }}</td>
                                <td>
                                    <i class="fas {{ $module->icon }} me-2"></i>
                                    {{ $module->name }}
                                </td>
                                <td>{{ $module->description }}</td>
                                <td><code>{{ $module->route }}</code></td>
                                <td>{{ $module->version }}</td>
                                <td>
                                    @if($module->active)
                                        <span class="badge bg-success">Aktiv</span>
                                    @else
                                        <span class="badge bg-danger">Inaktiv</span>
                                    @endif
                                </td>
                                <td>{{ $module->order }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('modules.show', $module) }}" class="btn btn-info" title="Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('modules.edit', $module) }}" class="btn btn-warning" title="Bearbeiten">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('modules.toggle-active', $module) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $module->active ? 'btn-secondary' : 'btn-success' }}" title="{{ $module->active ? 'Deaktivieren' : 'Aktivieren' }}">
                                                <i class="fas {{ $module->active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" title="Löschen" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $module->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $module->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $module->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $module->id }}">Modul löschen</h5>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Keine Module gefunden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Hinweise zur Modulverwaltung
                </div>
                <div class="card-body">
                    <p>Die Modulverwaltung ermöglicht es Ihnen, die verfügbaren Module zu verwalten und zu konfigurieren.</p>
                    
                    <h5>Wichtige Informationen:</h5>
                    <ul>
                        <li>Module können aktiviert oder deaktiviert werden</li>
                        <li>Die Reihenfolge bestimmt die Anzeige im Dashboard und in der Navigation</li>
                        <li>Jedes Modul hat eine eindeutige Route, die für die Navigation verwendet wird</li>
                        <li>Das Löschen eines Moduls entfernt auch alle zugehörigen Berechtigungen</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Die Module "Dashboard" und "Benutzerverwaltung" sind Kernmodule und sollten nicht deaktiviert werden.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-2"></i> Modulberechtigungen
                </div>
                <div class="card-body">
                    <p>Für jedes Modul können Berechtigungen für verschiedene Rollen vergeben werden:</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Stufe</th>
                                    <th>Beschreibung</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-secondary">read</span></td>
                                    <td>Nur Lesezugriff auf das Modul</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">write</span></td>
                                    <td>Lese- und Schreibzugriff auf das Modul</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-danger">admin</span></td>
                                    <td>Voller Zugriff auf das Modul, einschließlich Verwaltungsfunktionen</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <p class="mt-3">Die Berechtigungen werden über die Rollenverwaltung vergeben.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
