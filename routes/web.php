<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BhaktController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// Language switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Public Routes
Route::get('/', [BhaktController::class, 'create'])->name('register');
Route::post('/register', [BhaktController::class, 'store'])->name('register.store');
Route::get('/register/success/{bhakt}', [BhaktController::class, 'success'])->name('register.success');
Route::get('/register/receipt/{bhakt}', [BhaktController::class, 'receipt'])->name('register.receipt');
Route::get('/status', [BhaktController::class, 'status'])->name('status');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (protected)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bhakts', [AdminController::class, 'index'])->name('bhakts.index');
    Route::get('/bhakts/export', [AdminController::class, 'export'])->name('bhakts.export');
    Route::get('/bhakts/{bhakt}', [AdminController::class, 'show'])->name('bhakts.show');
    Route::delete('/bhakts/{bhakt}', [AdminController::class, 'destroy'])->name('bhakts.destroy');
    Route::post('/bhakts/{bhakt}/approve', [AdminController::class, 'approve'])->name('bhakts.approve');
    Route::post('/bhakts/{bhakt}/reject', [AdminController::class, 'reject'])->name('bhakts.reject');
    Route::post('/bhakts/{bhakt}/correction', [AdminController::class, 'correction'])->name('bhakts.correction');
});
