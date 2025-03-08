@extends('layouts.app')

@section('title', 'Modul bearbeiten')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Modul bearbeiten</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('modules.update', $module) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $module->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Beschreibung</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $module->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="route" class="form-label">Route</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('route') is-invalid @enderror" id="route" name="route" value="{{ old('route', $module->route) }}" required>
                                    @error('route')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    Die Route wird für die Navigation verwendet (z.B. "users" für "/users").
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="icon" class="form-label">Icon (Font Awesome)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas {{ $module->icon }}"></i></span>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $module->icon) }}">
                                    @error('icon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    Nur den Icon-Namen eingeben (z.B. "fa-users").
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="version" class="form-label">Version</label>
                                <input type="text" class="form-control @error('version') is-invalid @enderror" id="version" name="version" value="{{ old('version', $module->version) }}">
                                @error('version')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="order" class="form-label">Reihenfolge</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $module->order) }}" min="1">
                                @error('order')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $module->active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active">Aktiv</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Rollenberechtigungen</label>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Rolle</th>
                                            <th>Beschreibung</th>
                                            <th>Berechtigung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}" 
                                                            {{ in_array($role->id, old('roles', $moduleRoles)) || $role->name == 'Admin' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="role{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{ $role->description }}</td>
                                                <td>
                                                    <select class="form-select form-select-sm" name="permissions[]" id="permission{{ $role->id }}">
                                                        <option value="read" {{ (old('permissions.' . $loop->index, $rolePermissions[$role->id] ?? '')) == 'read' ? 'selected' : '' }}>Lesen</option>
                                                        <option value="write" {{ (old('permissions.' . $loop->index, $rolePermissions[$role->id] ?? '')) == 'write' ? 'selected' : '' }}>Schreiben</option>
                                                        <option value="admin" {{ (old('permissions.' . $loop->index, $rolePermissions[$role->id] ?? '')) == 'admin' || $role->name == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @error('roles')
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
                            <a href="{{ route('modules.index') }}" class="btn btn-outline-secondary">
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
                    <i class="fas fa-info-circle me-2"></i> Modulinformationen
                </div>
                <div class="card-body">
                    <p><strong>Modul-ID:</strong> {{ $module->id }}</p>
                    <p><strong>Erstellt am:</strong> {{ $module->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Letzte Aktualisierung:</strong> {{ $module->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2"></i> Hinweise
                </div>
                <div class="card-body">
                    <p>Bitte beachten Sie bei der Bearbeitung eines Moduls:</p>
                    <ul>
                        <li>Der Name des Moduls muss eindeutig sein</li>
                        <li>Die Route muss eindeutig sein und darf keine Sonderzeichen enthalten</li>
                        <li>Änderungen an den Berechtigungen wirken sich sofort auf alle Benutzer aus</li>
                        <li>Wenn Sie ein Modul deaktivieren, ist es für alle Benutzer nicht mehr zugänglich</li>
                    </ul>
                    
                    <h6 class="mt-3">Berechtigungsstufen:</h6>
                    <ul>
                        <li><strong>Lesen:</strong> Nur Lesezugriff auf das Modul</li>
                        <li><strong>Schreiben:</strong> Lese- und Schreibzugriff auf das Modul</li>
                        <li><strong>Admin:</strong> Voller Zugriff auf das Modul, einschließlich Verwaltungsfunktionen</li>
                    </ul>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Die Rolle "Admin" erhält automatisch Admin-Berechtigungen für alle Module.
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
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        
        roleCheckboxes.forEach(checkbox => {
            const roleId = checkbox.value;
            const permissionSelect = document.getElementById('permission' + roleId);
            
            // Initial-Status setzen
            permissionSelect.disabled = !checkbox.checked;
            
            // Event-Listener für Änderungen
            checkbox.addEventListener('change', function() {
                permissionSelect.disabled = !this.checked;
            });
        });
        
        // Icon-Vorschau aktualisieren
        const iconInput = document.getElementById('icon');
        const iconPreview = iconInput.previousElementSibling.querySelector('i');
        
        iconInput.addEventListener('input', function() {
            // Entferne alle Klassen außer 'fas'
            iconPreview.className = 'fas';
            // Füge die neue Icon-Klasse hinzu
            iconPreview.classList.add(this.value);
        });
    });
</script>
@endsection
