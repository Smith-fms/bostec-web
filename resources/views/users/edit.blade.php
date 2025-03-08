@extends('layouts.app')

@section('title', 'Benutzer bearbeiten')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Benutzer bearbeiten</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="vorname" class="form-label">Vorname</label>
                                <input type="text" class="form-control @error('vorname') is-invalid @enderror" id="vorname" name="vorname" value="{{ old('vorname', $user->vorname) }}" required>
                                @error('vorname')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail-Adresse</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Neues Passwort</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    Lassen Sie dieses Feld leer, wenn Sie das Passwort nicht ändern möchten.
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Passwort bestätigen</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Rollen</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}" 
                                                {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                    <i class="fas fa-info-circle me-2"></i> Benutzerinformationen
                </div>
                <div class="card-body">
                    <p><strong>Benutzer-ID:</strong> {{ $user->id }}</p>
                    <p><strong>Registriert am:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Letzte Aktualisierung:</strong> {{ $user->updated_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2"></i> Hinweise
                </div>
                <div class="card-body">
                    <ul>
                        <li>Die E-Mail-Adresse muss eindeutig sein</li>
                        <li>Wenn Sie das Passwort ändern, muss es mindestens 8 Zeichen lang sein</li>
                        <li>Der Benutzer sollte mindestens eine Rolle haben</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Die Rolle "Admin" gewährt vollen Zugriff auf alle Funktionen!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
