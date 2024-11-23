<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BidController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// root route
Route::get('/', function () {
    return redirect()->route('home');
});


// home page
Route::get('/home', [HomeController::class, 'index'])->name('home');

// dashboard - protected
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// profile - protected
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // profile settings
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
});

// authentication routes
require __DIR__.'/auth.php';

// language switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// search
Route::get('/search', [HomeController::class, 'search'])->name('search');

// auctions
Route::resource('auctions', AuctionController::class);

// bids
Route::resource('bids', BidController::class);

// auctions followed by the authenticated user
Route::get('/auction/followed', [AuctionController::class, 'followed'])->middleware('auth')->name('auction.followed');

// categories
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// about
Route::get('/about', function () {
    return view('about');
})->name('about');

// contact
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
});

Route::get('/messages', function () {
    return view('messages');
})->name('messages');

/*
// Rotas de Autenticação Personalizadas
Route::middleware('guest')->group(function () {
    // Formulário de Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

    // Processar Login
    Route::post('login', [LoginController::class, 'login']);

    // Formulário de Registro
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Processar Registro
    Route::post('register', [RegisterController::class, 'register']);
});

// Processar Logout
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

*/