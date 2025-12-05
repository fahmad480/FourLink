<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkGroupController;
use App\Http\Controllers\LinkComponentController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminController;

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

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Public Link Group View
Route::get('/l/{slug}', [PublicController::class, 'show'])->name('public.show');
Route::post('/l/{slug}/verify', [PublicController::class, 'verifyPassword'])->name('public.verify');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Link Groups
    Route::resource('link-groups', LinkGroupController::class)->except(['index']);
    Route::get('/my-links', [LinkGroupController::class, 'index'])->name('link-groups.index');
    
    // Link Components
    Route::prefix('link-groups/{linkGroup}')->name('components.')->group(function () {
        Route::get('/components/create', [LinkComponentController::class, 'create'])->name('create');
        Route::post('/components', [LinkComponentController::class, 'store'])->name('store');
        Route::get('/components/{component}/edit', [LinkComponentController::class, 'edit'])->name('edit');
        Route::put('/components/{component}', [LinkComponentController::class, 'update'])->name('update');
        Route::delete('/components/{component}', [LinkComponentController::class, 'destroy'])->name('destroy');
        Route::post('/components/reorder', [LinkComponentController::class, 'reorder'])->name('reorder');
    });
    
    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // All Link Groups
        Route::get('/link-groups', [AdminController::class, 'linkGroups'])->name('link-groups');
    });
});

