@extends('layouts.app')

@section('title', 'Rollenverwaltung')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Rollenverwaltung</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Neue Rolle
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
                            <th>Benutzer</th>
                            <th>Module</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $role->users_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $role->modules->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('roles.show', $role) }}" class="btn btn-info" title="Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning" title="Bearbeiten">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" title="Löschen" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $role->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $role->id }}">Rolle löschen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($role->users_count > 0)
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> Diese Rolle ist noch {{ $role->users_count }} Benutzer(n) zugewiesen und kann nicht gelöscht werden.
                                                        </div>
                                                        <p>Bitte entfernen Sie zuerst die Rolle von allen Benutzern.</p>
                                                    @else
                                                        <p>Sind Sie sicher, dass Sie die Rolle <strong>{{ $role->name }}</strong> löschen möchten?</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                                    @if($role->users_count == 0)
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Keine Rollen gefunden</td>
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
                    <i class="fas fa-info-circle me-2"></i> Hinweise zur Rollenverwaltung
                </div>
                <div class="card-body">
                    <p>Die Rollenverwaltung ermöglicht es Ihnen, Benutzerrollen zu definieren und zu verwalten. Jede Rolle kann verschiedene Berechtigungen für verschiedene Module haben.</p>
                    
                    <h5>Wichtige Informationen:</h5>
                    <ul>
                        <li>Eine Rolle kann nicht gelöscht werden, wenn sie noch Benutzern zugewiesen ist</li>
                        <li>Die Rolle "Admin" sollte nur vertrauenswürdigen Benutzern zugewiesen werden</li>
                        <li>Jeder Benutzer kann mehrere Rollen haben</li>
                        <li>Die Berechtigungen eines Benutzers ergeben sich aus der Kombination aller seiner Rollen</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-2"></i> Berechtigungsstufen
                </div>
                <div class="card-body">
                    <p>Für jedes Modul können folgende Berechtigungsstufen vergeben werden:</p>
                    
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
                </div>
            </div>
        </div>
    </div>
@endsection
