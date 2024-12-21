<?php


use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAuctionController;
use Illuminate\Support\Facades\Route;

// página dos admins

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/search', [AdminUserController::class, 'search'])->name('admin.users.search');
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('admin.users.block');
    Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');

    // Rotas de admin para leilões
    Route::get('/auctions', [AdminAuctionController::class, 'index'])->name('admin.auctions.index');
    Route::get('/auctions/{auction}', [AdminAuctionController::class, 'show'])->name('admin.auctions.show');

    // Cancelar
    Route::post('/auctions/{auction}/cancel', [AdminAuctionController::class, 'cancel'])
        ->name('admin.auctions.cancel');

    // Suspender
    Route::post('/auctions/{auction}/suspend', [AdminAuctionController::class, 'suspend'])
        ->name('admin.auctions.suspend');

    // Reativar (remover suspensão)
    Route::post('/auctions/{auction}/unsuspend', [AdminAuctionController::class, 'unsuspend'])
        ->name('admin.auctions.unsuspend');
});


