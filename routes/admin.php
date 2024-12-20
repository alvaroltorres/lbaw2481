<?php


use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

// página dos admins

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/search', [AdminUserController::class, 'search'])->name('admin.users.search');
    Route::post('/users/{user}/block', [AdminUserController::class, 'block'])->name('admin.users.block');
    Route::post('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('admin.users.unblock');
    Route::post('/admin/auctions/{auction}/cancel', [AdminAuctionController::class, 'cancel'])->name('admin.auctions.cancel');
});


