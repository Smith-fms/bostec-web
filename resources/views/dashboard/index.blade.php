@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Willkommen, {{ $user->vorname }} {{ $user->name }}!</h5>
                    <p class="card-text">
                        Willkommen bei BostecWeb, der Technologieplattform für Behörden und Organisationen mit Sicherheitsaufgaben (BOS).
                        Hier finden Sie alle verfügbaren Module und Funktionen.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="h4 mb-3">Verfügbare Module</h2>

    <div class="row">
        @foreach($modules as $module)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 module-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="fas {{ $module->icon }} fa-2x text-primary"></i>
                            </div>
                            <h5 class="card-title mb-0">{{ $module->name }}</h5>
                        </div>
                        <p class="card-text">{{ $module->description }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="{{ route($module->route.'.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-right me-1"></i> Öffnen
                        </a>
                        <span class="badge bg-secondary float-end">v{{ $module->version }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @can('manage-users')
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i> Benutzerstatistik
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Gesamtzahl Benutzer</td>
                                        <td class="text-end">{{ App\Models\User::count() }}</td>
                                    </tr>
                                    @foreach(App\Models\Role::withCount('users')->get() as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td class="text-end">{{ $role->users_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                            Benutzerverwaltung öffnen
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-puzzle-piece me-2"></i> Modulstatistik
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td>Gesamtzahl Module</td>
                                        <td class="text-end">{{ App\Models\Module::count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Aktive Module</td>
                                        <td class="text-end">{{ App\Models\Module::where('active', true)->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td>Inaktive Module</td>
                                        <td class="text-end">{{ App\Models\Module::where('active', false)->count() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('modules.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                            Modulverwaltung öffnen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
