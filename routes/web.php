<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MovieController;
Route::get('/', function () {
    return view('welcome');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/search-movies', [MovieController::class, 'search'])->name('movies.search');
    Route::post('/save-favorite', [MovieController::class, 'saveFavorite'])->middleware('auth');
    Route::get('/movies', [MovieController::class, 'showFavorites'])->name('movies.favorites');
    Route::get('/api-keys', [ApiKeyController::class, 'showApiKeys'])->name('api-keys.show');
    Route::post('/save-api-key', [ApiKeyController::class, 'saveApiKey'])->name('api-keys.save');
    Route::post('/api-keys/update/{id}', [ApiKeyController::class, 'updateApiKey'])->name('api-keys.update');
    Route::delete('/api-keys/delete/{id}', [ApiKeyController::class, 'deleteApiKey'])->name('api-keys.delete');
});






require __DIR__.'/auth.php';
