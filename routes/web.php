<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EditorsPicksController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsletterPageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SponsoredFeatureController;
use Illuminate\Support\Facades\Route;

//Route::inertia('/', 'welcome')->name('home');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::post('/newsletter', NewsletterController::class)->name('newsletter.store');
Route::get('/newsletter', [NewsletterPageController::class, 'index'])->name('newsletter');

Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/editors-picks', [EditorsPicksController::class, 'index'])->name('editors-picks');

Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/legal/{slug}', [LegalController::class, 'show'])->name('legal.show');
Route::get('/features/{slug}', [SponsoredFeatureController::class, 'show'])->name('sponsored-features.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');    
});

require __DIR__.'/settings.php';
