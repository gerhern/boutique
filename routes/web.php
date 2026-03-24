<?php

use App\Http\Controllers\RaffleController;
use Illuminate\Support\Facades\Route;

Route::post('/raffle/{raffle}/entry', [RaffleController::class, 'entry'])
    ->name('raffle.entry');
Route::get('/raffle/{raffle}/users', [RaffleController::class, 'users'])
    ->name('raffle.users');
