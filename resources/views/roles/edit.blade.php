@extends('layouts.app')

@section('title', 'Rolle bearbeiten')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Rolle bearbeiten</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Beschreibung</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Modulberechtigungen</label>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Modul</th>
                                            <th>Beschreibung</th>
                                            <th>Berechtigung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($modules as $module)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $module->id }}" id="module{{ $module->id }}" 
                                                            {{ in_array($module->id, old('modules', array_keys($modulePermissions))) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="module{{ $module->id }}">
                                                            <i class="fas {{ $module->icon }} me-2"></i>
                                                            {{ $module->name }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{ $module->description }}</td>
                                                <td>
                                                    <select class="form-select form-select-sm" name="permissions[]" id="permission{{ $module->id }}">
                                                        <option value="read" {{ (old('permissions.' . $loop->index, $modulePermissions[$module->id] ?? '')) == 'read' ? 'selected' : '' }}>Lesen</option>
                                                        <option value="write" {{ (old('permissions.' . $loop->index, $modulePermissions[$module->id] ?? '')) == 'write' ? 'selected' : '' }}>Schreiben</option>
                                                        <option value="admin" {{ (old('permissions.' . $loop->index, $modulePermissions[$module->id] ?? '')) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @error('modules')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            @error('permissions')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Zurück
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Rolleninformationen
                </div>
                <div class="card-body">
                    <p><strong>Rollen-ID:</strong> {{ $role->id }}</p>
                    <p><strong>Erstellt am:</strong> {{ $role->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Letzte Aktualisierung:</strong> {{ $role->updated_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Zugewiesene Benutzer:</strong> {{ $role->users->count() }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2"></i> Hinweise
                </div>
                <div class="card-body">
                    <p>Bitte beachten Sie bei der Bearbeitung einer Rolle:</p>
                    <ul>
                        <li>Der Name der Rolle muss eindeutig sein</li>
                        <li>Änderungen an den Berechtigungen wirken sich sofort auf alle Benutzer mit dieser Rolle aus</li>
                        <li>Wenn Sie Module deaktivieren, verlieren Benutzer mit dieser Rolle den Zugriff auf diese Module</li>
                    </ul>
                    
                    <h6 class="mt-3">Berechtigungsstufen:</h6>
                    <ul>
                        <li><strong>Lesen:</strong> Nur Lesezugriff auf das Modul</li>
                        <li><strong>Schreiben:</strong> Lese- und Schreibzugriff auf das Modul</li>
                        <li><strong>Admin:</strong> Voller Zugriff auf das Modul, einschließlich Verwaltungsfunktionen</li>
                    </ul>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Seien Sie vorsichtig bei der Vergabe von Admin-Berechtigungen!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aktiviere/Deaktiviere Berechtigungsauswahl basierend auf Checkbox-Status
        const moduleCheckboxes = document.querySelectorAll('input[name="modules[]"]');
        
        moduleCheckboxes.forEach(checkbox => {
            const moduleId = checkbox.value;
            const permissionSelect = document.getElementById('permission' + moduleId);
            
            // Initial-Status setzen
            permissionSelect.disabled = !checkbox.checked;
            
            // Event-Listener für Änderungen
            checkbox.addEventListener('change', function() {
                permissionSelect.disabled = !this.checked;
            });
        });
    });
</script>
@endsection
