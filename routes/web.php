<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

// --- ACCÈS PUBLIC (INVITÉ) ---
Route::get('/', [DashboardController::class, 'guestIndex'])->name('guest.index');
Route::get('/resources/{id}', [DashboardController::class, 'resourceDetail'])->name('resource.show');

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Inscription
Route::get('/register-request', [DashboardController::class, 'showRegisterForm'])->name('register.request');
Route::post('/register-request', [AuthController::class, 'register']);

// --- ESPACES SÉCURISÉS ---
Route::middleware(['auth'])->group(function () {

    // Utilisateur Interne
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    });

    // Responsable Technique
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    });

    // Administrateur
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    });
});