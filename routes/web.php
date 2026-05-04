<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DocenteController;
use App\Http\Controllers\Admin\ArchivoController as AdminArchivoController;
use App\Http\Controllers\Docente\DashboardController as DocenteDashboard;
use App\Http\Controllers\Docente\ArchivoController as DocenteArchivoController;

// ──────────────────────────────────────────────────────────────────────────────
// RUTAS PÚBLICAS — Autenticación
// ──────────────────────────────────────────────────────────────────────────────

// Redirigir raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Formulario de login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Procesar el intento de login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ──────────────────────────────────────────────────────────────────────────────
// RUTAS ADMIN — Protegidas con middleware 'auth' y 'admin'
// ──────────────────────────────────────────────────────────────────────────────
Route::prefix('admin')
     ->name('admin.')
     ->middleware(['auth', 'admin'])
     ->group(function () {

    // Dashboard principal del administrador
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // ── Gestión de Docentes ────────────────────────────────────────────────
    Route::resource('docentes', DocenteController::class)
         ->except(['destroy']); // No se elimina docentes, solo se desactivan

    // Ruta especial para activar/desactivar un docente (toggle)
    Route::patch('docentes/{docente}/toggle-activo', [DocenteController::class, 'toggleActivo'])
         ->name('docentes.toggleActivo');

    // ── Visualización de Archivos ──────────────────────────────────────────
    Route::get('/archivos', [AdminArchivoController::class, 'index'])->name('archivos.index');
    Route::get('/archivos/{archivo}/download', [AdminArchivoController::class, 'download'])->name('archivos.download');
    Route::delete('/archivos/{archivo}', [AdminArchivoController::class, 'destroy'])->name('archivos.destroy');
});

// ──────────────────────────────────────────────────────────────────────────────
// RUTAS DOCENTE — Protegidas con middleware 'auth' y 'docente'
// ──────────────────────────────────────────────────────────────────────────────
Route::prefix('docente')
     ->name('docente.')
     ->middleware(['auth', 'docente'])
     ->group(function () {

    // Dashboard del docente
    Route::get('/dashboard', [DocenteDashboard::class, 'index'])->name('dashboard');

    // ── Gestión de Archivos del Docente ───────────────────────────────────
    Route::get('/archivos', [DocenteArchivoController::class, 'index'])->name('archivos.index');
    Route::get('/archivos/subir', [DocenteArchivoController::class, 'create'])->name('archivos.create');
    Route::post('/archivos', [DocenteArchivoController::class, 'store'])->name('archivos.store');
    Route::get('/archivos/{archivo}/download', [DocenteArchivoController::class, 'download'])->name('archivos.download');
    Route::delete('/archivos/{archivo}', [DocenteArchivoController::class, 'destroy'])->name('archivos.destroy');
});
