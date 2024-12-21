<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditController;
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
    Route::patch('/profile-picture/', [ProfileController::class, 'storeProfilePicture'])->name('profile.picture.store');
    Route::get('/profile-picture/{user_id}', [ProfileController::class, 'showProfilePicture'])->name('profile.picture');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/ratings', [ProfileController::class, 'ratings'])->name('profile.ratings');
    Route::get('/profile/myauctions', [ProfileController::class, 'myAuctions'])->name('profile.myauctions');
    Route::get('/profile/soldauctions', [AuctionController::class, 'soldAuctions'])->name('profile.soldauctions');

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

Route::get('/profile/bidding-history', [AuctionController::class, 'biddingHistoryForUser'])->name('profile.biddingHistory');


// Route to display the form to add credits
Route::get('/credits/add', [CreditController::class, 'showAddCreditsForm'])->name('credits.add');

// Route to process adding credits (POST)
Route::post('/credits/add', [CreditController::class, 'addCredits'])->name('credits.add.post');

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

// Main Features
Route::get('/features', function () {
    return view('features');
})->name('features');

Route::middleware('auth')->group(function () {
    Route::get('/followed-auctions', [AuctionController::class, 'followedAuctions'])->name('auctions.followed');

    Route::post('/auction/{auction_id}/follow', [AuctionController::class, 'followAuction'])->name('auction.follow');
    Route::delete('/auction/{auction_id}/unfollow', [AuctionController::class, 'unfollowAuction'])->name('auction.unfollow');


    Route::get('/notifications/fetch', [NotificationController::class, 'fetchNewNotifications'])
        ->name('notifications.fetch');

    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])
        ->name('notifications.unreadCount');

    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');

    Route::get('/notifications/show/{id}', [NotificationController::class, 'show'])
        ->name('notifications.show');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
});

use App\Http\Controllers\MessageController;

Route::middleware('auth')->group(function() {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/chat', [MessageController::class, 'loadChat'])->name('messages.loadChat');
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
    Route::post('/messages/start', [MessageController::class, 'startChat'])->name('messages.start');

    // Polling de novas mensagens
    Route::get('/messages/poll', [MessageController::class, 'pollMessages'])->name('messages.poll');
    // Polling de novos chats (leilões)
    Route::get('/messages/poll-chats', [MessageController::class, 'pollChats'])->name('messages.pollChats');
});

Route::get('/user/{user}', [AdminUserController::class, 'show'])->name('user.show');

// Rota para exibir a página de encerramento do leilão
Route::get('/auctions/{auction}/end', [AuctionController::class, 'showEndAuction'])
    ->name('auctions.end.view')
    ->middleware('auth');

Route::put('/auctions/{auction}/end', [AuctionController::class, 'endAuction'])
    ->name('auctions.end')
    ->middleware('auth');

Route::post('/auctions/{auction}/end-manually', [AuctionController::class, 'endManually'])
    ->name('auctions.endManually')
    ->middleware('auth');

Route::put('/auctions/{auction}/cancel', [AuctionController::class, 'cancelAuction'])
    ->name('auctions.cancel')
    ->middleware('auth');

// Rota para processar o encerramento do leilão
Route::put('/auctions/{auction}/end', [AuctionController::class, 'endAuction'])->name('auctions.end')->middleware('auth');

Route::get('/ratings/{transaction_id}/create', [RatingController::class, 'create'])->name('ratings.create');
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
Route::get('/users/{user_id}/ratings', [RatingController::class, 'index'])->name('ratings.index');


use App\Http\Controllers\RatingController;

Route::group(['middleware' => 'auth'], function() {
    // Para criar o rating
    Route::get('/ratings/{transaction_id}/create', [RatingController::class, 'create'])
        ->name('ratings.create');
    Route::post('/ratings', [RatingController::class, 'store'])
        ->name('ratings.store');

    // Para ver as avaliações de um determinado user
    Route::get('/users/{user_id}/ratings', [RatingController::class, 'index'])
        ->name('ratings.index');
});

require __DIR__.'/admin.php';

