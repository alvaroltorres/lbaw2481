<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root route
Route::get('/', function () {
    return redirect()->route('home');
});

// Home page
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Dashboard - protected
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile - protected
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile settings
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
});

// Authentication routes
require __DIR__.'/auth.php';

// Language switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Search
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Auctions
Route::resource('auctions', AuctionController::class);

// Bids - store route for placing bids on auctions
Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->name('bids.store');

// Bidding history
Route::get('/auctions/{auction}/bidding-history', [AuctionController::class, 'biddingHistory'])->name('auctions.biddingHistory');

// Auctions followed by the authenticated user
Route::get('/auction/followed', [AuctionController::class, 'followed'])->middleware('auth')->name('auction.followed');

// Categories
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// About
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
});

// Messages
Route::get('/messages', function () {
    return view('messages');
})->name('messages');

Route::middleware('admin')->group(function () {
Route::get('/admin/user', [AdminUserController::class, 'index'])->name('admin.users.index');

Route::get('/admin/user/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');

Route::delete('/admin/user/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

Route::patch('/admin/user/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

Route::get('/admin/user/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
});