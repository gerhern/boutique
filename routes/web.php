<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaffleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    //Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Raffles
    Route::post('/raffle/{raffle}/entry', [RaffleController::class, 'entry'])
        ->name('raffle.entry');
    Route::get('/raffle/{raffle}/users', [RaffleController::class, 'users'])
        ->name('raffle.users');

    //Products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
});


require __DIR__.'/auth.php';
