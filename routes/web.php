<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaffleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware('auth')->group(function () {
    Route::middleware('checkAdminRole')->group(function () {
        //Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //Admin.Raffles
        Route::get('/dashboard/raffles', [AdminController::class, 'raffles'])
            ->name('admin.raffles.index');
        //Admin.Products
        Route::get('/dashboard/products', [AdminController::class, 'products'])
            ->name('admin.products.index');
        Route::get('/dashboard/products/create', [ProductController::class, 'create'])
            ->name('admin.products.create');
        Route::post('/dashboard/products/store', [ProductController::class, 'store'])
            ->name('admin.products.store');
    });
    //Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard/notifications', [AdminController::class, 'notifications'])
        ->name('admin.notifications.index');
    Route::get('/dashboard/messages', [AdminController::class, 'messages'])
        ->name('admin.messages.index');
    Route::get('/dashboard/settings', [AdminController::class, 'settings'])
        ->name('admin.settings');

    Route::get('/dashboard/products/edit', [AdminController::class, 'edit'])
        ->name('admin.products.edit');
    Route::get('/dashboard/products/show', [AdminController::class, 'show'])
        ->name('admin.products.show');


    Route::get('/dashboard/raffles/history', [AdminController::class, 'history'])
        ->name('admin.raffles.history');

    //Admin.Categories
    Route::get('/dashboard/categories', [AdminController::class, 'categories'])
        ->name('admin.categories.index');

    //Raffles
    Route::get('/raffle', [RaffleController::class, 'index'])
        ->name('raffles.index');
    Route::get('/raffles/{raffle}', [RaffleController::class, 'show'])
        ->name('raffles.show');
    Route::post('/raffle/{raffle}/entry', [RaffleController::class, 'entry'])
        ->name('raffles.entry');
    Route::get('/raffle/{raffle}/users', [RaffleController::class, 'users'])
        ->name('raffles.users');

    //Products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');

    //Contact
    Route::get('contact', [ContactController::class, 'index'])
        ->name('contact');
});


require __DIR__ . '/auth.php';
