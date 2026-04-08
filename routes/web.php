<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
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
        Route::get('/admin/raffles', [RaffleController::class, 'adminIndex'])
            ->name('admin.raffles.index');

        Route::get('/admin/raffles/create/{product}', [RaffleController::class, 'create'])
            ->name('admin.raffles.create');

        Route::post('/admin/raffles/store', [RaffleController::class, 'store'])
            ->name('admin.raffles.store');

        Route::get('/admin/raffles/show/{raffle}', [RaffleController::class, 'show'])
            ->name('admin.raffles.show');

        Route::put('/admin/raffles/edit/{raffle}', [RaffleController::class, 'edit'])
            ->name('admin.raffles.edit');


        //Admin.Products
        Route::get('/admin/products', [ProductController::class, 'adminIndex'])
            ->name('admin.products.index');

        Route::get('/admin/products/create', [ProductController::class, 'create'])
            ->name('admin.products.create');

        Route::post('/admin/products/store', [ProductController::class, 'store'])
            ->name('admin.products.store');

        Route::get('/admin/products/{product}', [ProductController::class, 'adminShow'])
            ->name('admin.products.show');

        Route::get('/admin/products/edit/{product}', [ProductController::class, 'edit'])
                ->name('admin.products.edit');

        Route::put('/admin/products/update/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');

        //Admin.Categories
        Route::get('/admin/categories', [CategoryController::class, 'index'])
            ->name('admin.categories.index');

        Route::post('/admin/categories', [CategoryController::class, 'store'])
            ->name('admin.categories.store');

        Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])
            ->name('admin.categories.update');

        Route::delete('/admin/categories/destroy/{category}', [CategoryController::class, 'destroy'])
            ->name('admin.categories.destroy');
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


    Route::get('/dashboard/raffles/history', [AdminController::class, 'history'])
        ->name('admin.raffles.history');

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
