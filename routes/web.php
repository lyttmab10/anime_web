<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\UserRelationshipController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AnimeController::class, 'index'])->name('home');
Route::get('/anime', [AnimeController::class, 'index'])->name('anime.index');
Route::get('/anime/{anime}', [AnimeController::class, 'show'])->name('anime.show');
Route::get('/anime/random', [AnimeController::class, 'random'])->name('anime.random');
Route::get('/anime/compare', [AnimeController::class, 'compareForm'])->name('anime.compare.form');
Route::post('/anime/compare', [AnimeController::class, 'compare'])->name('anime.compare');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// News routes - accessible to all users
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::post('/watchlist/toggle', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');
    Route::put('/watchlist/{watchlist}', [WatchlistController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    Route::get('/watchlist/check/{animeId}', [WatchlistController::class, 'checkStatus'])->name('watchlist.check');
    
    // User relationship routes
    Route::post('/user/{user}/follow', [UserRelationshipController::class, 'follow'])->name('user.follow');
    Route::delete('/user/{user}/unfollow', [UserRelationshipController::class, 'unfollow'])->name('user.unfollow');
    Route::post('/user/{user}/friend-request', [UserRelationshipController::class, 'sendFriendRequest'])->name('user.friend.request');
    Route::post('/friend-request/{id}/accept', [UserRelationshipController::class, 'acceptFriendRequest'])->name('friend.request.accept');
    Route::delete('/friend-request/{id}/decline', [UserRelationshipController::class, 'declineFriendRequest'])->name('friend.request.decline');
    Route::delete('/user/{user}/friend-request', [UserRelationshipController::class, 'cancelFriendRequest'])->name('friend.request.cancel');
    Route::delete('/user/{user}/unfriend', [UserRelationshipController::class, 'removeFriend'])->name('user.unfriend');
    Route::get('/user/{user}/friends', [UserRelationshipController::class, 'friendsPage'])->name('user.friends');
    Route::get('/user/{user}/following', [UserRelationshipController::class, 'followingPage'])->name('user.following');
    Route::get('/user/{user}/followers', [UserRelationshipController::class, 'followersPage'])->name('user.followers');
    Route::get('/user/pending-requests', [UserRelationshipController::class, 'friendRequestsPage'])->name('user.pending.requests');
    
    // Review routes
    Route::post('/anime/{anime}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/anime/{anime}/reviews', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/anime/{anime}/reviews', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Admin news routes - only for users who can manage news
    Route::middleware(['can:create,App\Models\News'])->group(function () {
        Route::get('/admin/news', [NewsController::class, 'adminIndex'])->name('news.admin.index');
        Route::get('/admin/news/create', [NewsController::class, 'create'])->name('news.admin.create');
        Route::post('/admin/news', [NewsController::class, 'store'])->name('news.admin.store');
        Route::get('/admin/news/{news}/edit', [NewsController::class, 'edit'])->name('news.admin.edit');
        Route::put('/admin/news/{news}', [NewsController::class, 'update'])->name('news.admin.update');
        Route::delete('/admin/news/{news}', [NewsController::class, 'destroy'])->name('news.admin.destroy');
    });
    
    // Admin anime routes - only for users who can access admin panel
    Route::middleware(['can:admin-access,App\Models\User'])->group(function () {
        Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('admin.create');
        Route::post('/admin', [\App\Http\Controllers\AdminController::class, 'store'])->name('admin.store');
        Route::get('/admin/{anime}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/admin/{anime}', [\App\Http\Controllers\AdminController::class, 'update'])->name('admin.update');
        Route::delete('/admin/{anime}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.destroy');
    });
});

// Image upload and display routes
Route::post('/upload', [ImageUploadController::class, 'upload']);
Route::get('/image/{type}/{filename}', [ImageUploadController::class, 'show'])->name('image.show');
Route::view('/demo', 'image_demo');

require __DIR__.'/auth.php';
