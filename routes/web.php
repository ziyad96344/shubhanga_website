<?php

use App\Http\Controllers\Admin;
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
Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->middleware('throttle:20,1')->name('checkout.coupon');
Route::post('/checkout/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('checkout.coupon.remove');
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

/*
|--------------------------------------------------------------------------
| Admin panel (/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('books', Admin\BookController::class)->except(['show']);
        Route::patch('books/{book}/toggle', [Admin\BookController::class, 'toggle'])->name('books.toggle');

        Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [Admin\OrderController::class, 'updateStatus'])->name('orders.update');

        Route::resource('coupons', Admin\CouponController::class)->except(['show']);

        Route::get('enquiries', [Admin\EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/{enquiry}', [Admin\EnquiryController::class, 'show'])->name('enquiries.show');
        Route::patch('enquiries/{enquiry}', [Admin\EnquiryController::class, 'update'])->name('enquiries.update');
        Route::delete('enquiries/{enquiry}', [Admin\EnquiryController::class, 'destroy'])->name('enquiries.destroy');
        Route::get('enquiries/{enquiry}/file/{field}', [Admin\EnquiryController::class, 'file'])->name('enquiries.file');

        Route::get('customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{phone}', [Admin\CustomerController::class, 'show'])->name('customers.show');

        Route::get('reports/sales', [Admin\ReportController::class, 'sales'])->name('reports.sales');

        Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings');
        Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
    });
});
