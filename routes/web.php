<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DocenteController;
use App\Http\Controllers\Admin\ArchivoController as AdminArchivoController;
use App\Http\Controllers\Admin\AnioLectivoController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\ReporteController;

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
// RUTA PÚBLICA FIRMADA — Para previsualización con Google Docs Viewer (sin sesión)
// Válida solo 10 minutos y con firma criptográfica de Laravel
// ──────────────────────────────────────────────────────────────────────────────
Route::get('/archivos/public-stream/{id}', [AdminArchivoController::class, 'streamPublico'])
     ->name('archivos.public-stream');

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

    // Consulta de datos por DNI (proxy a API RENIEC) — debe ir ANTES del resource
    Route::get('docentes/buscar-dni', [DocenteController::class, 'buscarDni'])
         ->name('docentes.buscarDni');

    Route::resource('docentes', DocenteController::class)
         ->except(['destroy']); // No se elimina docentes, solo se desactivan

    // Ruta especial para activar/desactivar un docente (toggle)
    Route::patch('docentes/{docente}/toggle-activo', [DocenteController::class, 'toggleActivo'])
         ->name('docentes.toggleActivo');

    // ── Gestión de Años Lectivos ──────────────────────────────────────────
    Route::get('/anios', [AnioLectivoController::class, 'index'])->name('anios.index');
    Route::post('/anios', [AnioLectivoController::class, 'store'])->name('anios.store');
    Route::patch('/anios/{anio}/activar', [AnioLectivoController::class, 'activar'])->name('anios.activar');



    // ── Visualización de Archivos ──────────────────────────────────────────
    Route::get('/archivos', [AdminArchivoController::class, 'index'])->name('archivos.index');
    Route::get('/archivos/papelera', [AdminArchivoController::class, 'papelera'])->name('archivos.papelera');
    Route::get('/archivos/{archivo}/download', [AdminArchivoController::class, 'download'])->name('archivos.download');
    Route::get('/archivos/{archivo}/stream', [AdminArchivoController::class, 'stream'])->name('archivos.stream');
    Route::get('/archivos/{archivo}/signed-url', [AdminArchivoController::class, 'signedUrl'])->name('archivos.signed-url');
    Route::delete('/archivos/{archivo}', [AdminArchivoController::class, 'destroy'])->name('archivos.destroy');
    Route::post('/archivos/{id}/restaurar', [AdminArchivoController::class, 'restaurar'])->name('archivos.restaurar');
    Route::delete('/archivos/{id}/forzar-eliminacion', [AdminArchivoController::class, 'forzarEliminacion'])->name('archivos.forzarEliminacion');

    // ── Reportes de Cumplimiento ───────────────────────────────────────────
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar', [ReporteController::class, 'exportarCsv'])->name('reportes.exportar');
    Route::get('/reportes/exportar-word', [ReporteController::class, 'exportarWord'])->name('reportes.exportarWord');

    // ── Configuración / Perfil ────────────────────────────────────────────
    Route::get('/configuracion',                 [ConfiguracionController::class, 'index'])->name('perfil');
    Route::post('/configuracion/perfil',         [ConfiguracionController::class, 'updatePerfil'])->name('perfil.update');
    Route::post('/configuracion/password',       [ConfiguracionController::class, 'updatePassword'])->name('perfil.password');
    Route::post('/configuracion/institucion',    [ConfiguracionController::class, 'updateInstitucion'])->name('perfil.institucion');
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
    Route::get('/archivos/papelera', [DocenteArchivoController::class, 'papelera'])->name('archivos.papelera');
    Route::get('/archivos/subir', [DocenteArchivoController::class, 'create'])->name('archivos.create');
    Route::post('/archivos', [DocenteArchivoController::class, 'store'])->name('archivos.store');
    Route::get('/archivos/{archivo}/download', [DocenteArchivoController::class, 'download'])->name('archivos.download');
    Route::get('/archivos/{archivo}/stream', [DocenteArchivoController::class, 'stream'])->name('archivos.stream');
    Route::get('/archivos/{archivo}/signed-url', [DocenteArchivoController::class, 'signedUrl'])->name('archivos.signed-url');
    Route::delete('/archivos/{archivo}', [DocenteArchivoController::class, 'destroy'])->name('archivos.destroy');
    Route::post('/archivos/{id}/restaurar', [DocenteArchivoController::class, 'restaurar'])->name('archivos.restaurar');
    Route::delete('/archivos/{id}/forzar-eliminacion', [DocenteArchivoController::class, 'forzarEliminacion'])->name('archivos.forzarEliminacion');
});
