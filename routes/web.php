<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Öffentliche Routen
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentifizierung
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Passwort zurücksetzen
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

// Geschützte Routen (nur für angemeldete Benutzer)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Benutzerprofil
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    
    Route::get('/profile/password', [AuthController::class, 'showChangePasswordForm'])->name('profile.password');
    Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password.update');

    // Benutzerverwaltung (nur für Administratoren)
    Route::middleware(['can:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Rollenverwaltung (nur für Administratoren)
    Route::middleware(['can:manage-roles'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Modulverwaltung (nur für Administratoren)
    Route::middleware(['can:manage-modules'])->group(function () {
        Route::resource('modules', ModuleController::class);
        Route::post('/modules/{module}/toggle-active', [ModuleController::class, 'toggleActive'])->name('modules.toggle-active');
    });
});
