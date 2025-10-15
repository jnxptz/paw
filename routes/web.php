<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/


// Show public landing page at root
Route::get('/', function () {
    return view('landing_public');
});


// Public landing
Route::get('/landing', [LandingController::class, 'index'])->name('landing');

// Client signup & login
Route::get('/signup', [RegisterController::class, 'show'])->name('register.form');
Route::post('/signup', [RegisterController::class, 'store'])->name('register.store');

// Chatbot (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
    Route::get('/chatbot/{id}', [ChatbotController::class, 'showConversation'])->name('chatbot.show');
    Route::post('/chatbot/new', [ChatbotController::class, 'newSession'])->name('chatbot.new');
    Route::delete('/chatbot/{id}', [ChatbotController::class, 'deleteSession'])->name('chatbot.delete');
});

// Admin signup
Route::prefix('admin')->group(function () {
    Route::get('/register', [AdminRegisterController::class, 'show'])->name('admin.register.form');
    Route::post('/register', [AdminRegisterController::class, 'store'])->name('admin.register.store');
});

// Login & Logout
Route::get('/login', [LoginController::class, 'show'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

// Products (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // Admin Products
        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProductController::class, 'index'])->name('admin.products');
            Route::post('/', [AdminProductController::class, 'store'])->name('admin.products.store');
            Route::put('/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
            Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        });

        // Manage Users
        Route::prefix('manage-users')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('users.index');
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });

        // Admin Profile
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');

        // Admin export
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    });

Route::post('/admin/change-password', [AdminController::class, 'changePassword'])->name('admin.changePassword');

/*
|--------------------------------------------------------------------------
| Client routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\ClientMiddleware::class])
    ->prefix('client')
    ->group(function () {
        Route::get('/landing', [ClientController::class, 'landing'])->name('client.landing');
        Route::get('/', [ClientController::class, 'index'])->name('client.dashboard');

        Route::get('/profile', [ClientController::class, 'show'])->name('client.profile');
        Route::get('/profile/edit', [ClientController::class, 'edit'])->name('client.profile.edit');
        Route::put('/profile', [ClientController::class, 'update'])->name('client.profile.update');

        Route::post('/change-password', [ClientController::class, 'changePassword'])->name('client.changePassword');
        Route::post('/logout', [ClientController::class, 'logout'])->name('client.logout');

        Route::put('/client/profile/update', [ClientController::class, 'updateProfile'])
    ->name('client.updateProfile');

    });
