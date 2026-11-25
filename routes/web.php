<?php

use App\Http\Controllers\Admin\AspirationController as AdminAspirationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Siswa\AspirationController as SiswaAspirationController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SchoolCare (Sistem Pengaduan Sarana Sekolah)
|--------------------------------------------------------------------------
*/

// Public Landing Page & Media
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/media/photo/{path}', [MediaController::class, 'showPhoto'])
    ->where('path', '.*')
    ->name('media.photo');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aspirasi', [AdminAspirationController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/{id}', [AdminAspirationController::class, 'show'])->name('aspirasi.show');
    Route::put('/aspirasi/{id}/status', [AdminAspirationController::class, 'updateStatus'])->name('aspirasi.update-status');
    Route::post('/aspirasi/{id}/feedback', [AdminAspirationController::class, 'storeFeedback'])->name('aspirasi.feedback');
    Route::delete('/aspirasi/{id}', [AdminAspirationController::class, 'destroy'])->name('aspirasi.destroy');
});

// Siswa Routes (Role: siswa)
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aspirasi', [SiswaAspirationController::class, 'index'])->name('aspirasi.index');
    Route::get('/aspirasi/buat', [SiswaAspirationController::class, 'create'])->name('aspirasi.create');
    Route::post('/aspirasi', [SiswaAspirationController::class, 'store'])->name('aspirasi.store');
    Route::get('/aspirasi/{id}', [SiswaAspirationController::class, 'show'])->name('aspirasi.show');
});
