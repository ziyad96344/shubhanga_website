<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Catalogue
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/suggest', [BookController::class, 'suggest'])->name('books.suggest');
Route::get('/books/{slug}', [BookController::class, 'show'])->name('books.show');

// Cart (session based; JSON for the drawer, redirects for no-JS forms)
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
Route::post('/cart/items', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{slug}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout and orders
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout.store');
Route::get('/checkout/pay/{number}', [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::post('/checkout/pay/{number}', [CheckoutController::class, 'verify'])->name('checkout.verify');
Route::get('/orders/{number}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/track-order', [OrderController::class, 'trackForm'])->name('orders.track');
Route::post('/track-order', [OrderController::class, 'track'])->middleware('throttle:10,1')->name('orders.track.submit');

// Content pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/free-resources', [PageController::class, 'resources'])->name('resources');
Route::get('/bookshops', [PageController::class, 'bookshops'])->name('bookshops');
Route::get('/policies/{slug}', [PageController::class, 'policy'])->name('policy');
Route::get('/blog', [PageController::class, 'blog'])->name('blog.index');
Route::get('/blog/{slug}', [PageController::class, 'post'])->name('blog.show');

Route::post('/newsletter', [EnquiryController::class, 'newsletter'])->middleware('throttle:6,1')->name('newsletter');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

// Forms (defined in config/forms.php)
$forms = 'specimen-copy|bulk-order|become-a-distributor|write-for-us|contact';
Route::get('/{form}', [EnquiryController::class, 'show'])->where('form', $forms)->name('forms.show');
Route::post('/{form}', [EnquiryController::class, 'store'])->where('form', $forms)->middleware('throttle:6,1')->name('forms.store');
