<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// ================= AUTH =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'registerPelanggan']);

Route::get('/logout', [AuthController::class, 'logout']);

// Route Dashboard (Satu pintu lewat DashboardController)
Route::get('/admin/dashboard', [DashboardController::class, 'index']);
Route::get('/owner/dashboard', [DashboardController::class, 'index']);
Route::get('/teknisi/dashboard', [DashboardController::class, 'index']);
Route::get('/pelanggan/dashboard', [DashboardController::class, 'index']);

// ================= DEFAULT =================
Route::get('/', function () {
    return redirect('/login');
});