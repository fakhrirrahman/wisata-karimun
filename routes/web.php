<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\WisataKunjunganController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PublicWisataController;

// Public Routes
Route::get('/', [PublicWisataController::class, 'index'])->name('beranda');
Route::get('/detail/{id}', [PublicWisataController::class, 'detail'])->name('detail');
Route::get('/peta', [PublicWisataController::class, 'peta'])->name('peta');
Route::get('/peta-kecamatan', function () {
    return view('public.peta-kecamatan');
})->name('peta-kecamatan');

// API Routes
Route::get('/api/wisata/all', [PublicWisataController::class, 'apiGetAllWisata'])->name('api.wisata.all');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Admin Routes (Protected)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/wisata-per-bulan', [DashboardController::class, 'getWisataPerBulan'])->name('dashboard.wisata-per-bulan');

    // Wisata Management
    Route::prefix('datawisata')->name('datawisata.')->group(function () {
        Route::get('/', [WisataController::class, 'index'])->name('index');
        Route::get('/create', [WisataController::class, 'create'])->name('create');
        Route::post('/', [WisataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [WisataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WisataController::class, 'update'])->name('update');
        Route::delete('/{id}', [WisataController::class, 'destroy'])->name('destroy');
    });

    // Kunjungan Management
    Route::prefix('kunjungan')->name('kunjungan.')->group(function () {
        Route::get('/', [WisataKunjunganController::class, 'index'])->name('index');
        Route::get('/statistik', [WisataKunjunganController::class, 'statistik'])->name('statistik');
        Route::get('/{id}', [WisataKunjunganController::class, 'show'])->name('show');
        Route::put('/{id}/reset', [WisataKunjunganController::class, 'resetVisits'])->name('reset');
    });

    // User Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');
        Route::put('/', [UserProfileController::class, 'update'])->name('update');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


