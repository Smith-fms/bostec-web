@extends('layouts.app')

@section('title', 'Passwort ändern')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Passwort ändern</h1>
    </div>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Aktuelles Passwort</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Neues Passwort</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Das Passwort muss mindestens 8 Zeichen lang sein.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Passwort bestätigen</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Zurück zum Profil
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Passwort ändern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i> Hinweise zur Passwortsicherheit
                </div>
                <div class="card-body">
                    <p>Ein sicheres Passwort sollte:</p>
                    <ul>
                        <li>Mindestens 8 Zeichen lang sein</li>
                        <li>Groß- und Kleinbuchstaben enthalten</li>
                        <li>Mindestens eine Zahl enthalten</li>
                        <li>Mindestens ein Sonderzeichen enthalten</li>
                        <li>Nicht leicht zu erraten sein (keine Namen, Geburtsdaten, etc.)</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Verwenden Sie niemals dasselbe Passwort für mehrere Dienste!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
