@extends('layouts.app')

@section('title', 'Mein Profil')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Mein Profil</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i> Persönliche Informationen
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <div class="form-control-plaintext">{{ Auth::user()->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Vorname</label>
                        <div class="form-control-plaintext">{{ Auth::user()->vorname }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">E-Mail-Adresse</label>
                        <div class="form-control-plaintext">{{ Auth::user()->email }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Registriert seit</label>
                        <div class="form-control-plaintext">{{ Auth::user()->created_at->format('d.m.Y') }}</div>
                    </div>
                    
                    <a href="{{ route('profile.password') }}" class="btn btn-primary">
                        <i class="fas fa-key me-1"></i> Passwort ändern
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-tag me-2"></i> Rollen und Berechtigungen
                </div>
                <div class="card-body">
                    <h5 class="card-title">Zugewiesene Rollen</h5>
                    <ul class="list-group mb-4">
                        @forelse(Auth::user()->roles as $role)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $role->name }}
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-check"></i>
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Keine Rollen zugewiesen</li>
                        @endforelse
                    </ul>
                    
                    <h5 class="card-title">Zugängliche Module</h5>
                    <ul class="list-group">
                        @forelse(App\Models\Module::getModulesForUser(Auth::user()) as $module)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas {{ $module->icon }} me-2"></i>
                                    {{ $module->name }}
                                </div>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $module->getPermissionLevelForUser(Auth::user()) }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Keine Module zugänglich</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-2"></i> Sicherheitshinweise
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Hinweis zur Datensicherheit</h5>
                        <p>
                            BostecWeb verarbeitet sensible Daten im Zusammenhang mit Einsätzen und möglicherweise auch Gesundheitsdaten.
                            Bitte beachten Sie die folgenden Sicherheitshinweise:
                        </p>
                        <ul>
                            <li>Teilen Sie Ihre Zugangsdaten niemals mit anderen Personen</li>
                            <li>Melden Sie sich nach der Nutzung immer ab</li>
                            <li>Verwenden Sie ein sicheres Passwort und ändern Sie es regelmäßig</li>
                            <li>Achten Sie darauf, dass keine unbefugten Personen Einsicht in Ihre Arbeit erhalten</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
