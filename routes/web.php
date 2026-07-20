<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/refresh', [DashboardController::class, 'refreshRisk'])->name('dashboard.refresh');

    // Countries & Comparison
    Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
    Route::get('/countries/compare', [CountryController::class, 'compare'])->name('countries.compare');
    Route::get('/countries/{code}', [CountryController::class, 'show'])->name('countries.show');

    // Weather
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');

    // Currency
    Route::get('/currency', [CurrencyController::class, 'index'])->name('currency.index');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');

    // Ports
    Route::get('/ports', [PortController::class, 'index'])->name('ports.index');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{code}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    
    // User CRUD
    Route::patch('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
    
    // Port CRUD
    Route::post('/admin/ports', [AdminController::class, 'storePort'])->name('admin.ports.store');
    Route::patch('/admin/ports/{id}', [AdminController::class, 'updatePort'])->name('admin.ports.update');
    Route::delete('/admin/ports/{id}', [AdminController::class, 'deletePort'])->name('admin.ports.destroy');
    
    // Article CRUD
    Route::post('/admin/articles', [AdminController::class, 'storeArticle'])->name('admin.articles.store');
    Route::patch('/admin/articles/{id}', [AdminController::class, 'updateArticle'])->name('admin.articles.update');
    Route::delete('/admin/articles/{id}', [AdminController::class, 'deleteArticle'])->name('admin.articles.destroy');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
