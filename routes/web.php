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

// Redirect root to login
Route::get('/', fn() => redirect()->route('login.form'));

// Client signup & login
Route::get('/signup', [RegisterController::class, 'show'])->name('register.form');
Route::post('/signup', [RegisterController::class, 'store'])->name('register.store');

// Chatbot (require auth to ensure user_id exists)
Route::middleware('auth')->group(function () {
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chat.send');
});
Route::get('/chatbot/{id}', [ChatBotController::class, 'showConversation'])->name('chatbot.show');





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

// Landing page & products (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::get('/landing', [LandingController::class, 'index'])->name('landing');
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

        // Admin export
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    });

/*
|--------------------------------------------------------------------------
| Client routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', \App\Http\Middleware\ClientMiddleware::class])
    ->prefix('client')
    ->group(function () {
        // Dashboard
        Route::get('/', [ClientController::class, 'index'])->name('client.dashboard');

        // Edit profile
        Route::get('/profile/edit', [ClientController::class, 'edit'])->name('client.profile.edit');
        Route::put('/profile', [ClientController::class, 'update'])->name('client.profile.update');

        


        // Logout
        Route::post('/logout', [ClientController::class, 'logout'])->name('client.logout');
    });

