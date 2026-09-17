<?php

use App\Http\Controllers\FoodController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('foods', FoodController::class)->except('show');
});

require __DIR__.'/settings.php';
