<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest.index');
});

Route::get('/home', [GuestController::class, 'index'])->name('home');
Route::get('/shop', [GuestController::class, 'shop'])->name('shop');
Route::get('/cart', [GuestController::class, 'cart'])->name('cart');
Route::get('/guest/products/{id}', [GuestController::class, 'showProduct'])->name('guest.products.show');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'buyer') {
        return redirect()->route('buyer.dashboard');
    } elseif ($user->role === 'artisan') {
        return redirect()->route('artisan.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/userlist', [AdminController::class, 'userlist'])->name('admin.userlist');
    Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});

// Buyer routes
Route::middleware(['auth', 'buyer'])->group(function () {
    Route::get('/buyer/dashboard', [BuyerController::class, 'dashboard'])->name('buyer.dashboard');
    Route::get('/buyer/shop', [BuyerController::class, 'shop'])->name('buyer.shop');
    Route::get('/buyer/cart', [BuyerController::class, 'cart'])->name('buyer.cart');
    Route::post('/cart/add', [BuyerController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [BuyerController::class, 'remove'])->name('cart.remove');
    Route::get('/buyer/checkout', [BuyerController::class, 'checkout'])->name('buyer.checkout');
    Route::post('/buyer/checkout', [BuyerController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/buyer/thankyou', [BuyerController::class, 'thankyou'])->name('buyer.thankyou');

    // Add this inside the buyer middleware group
Route::get('/products/{id}', [BuyerController::class, 'show'])->name('buyer.products.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
    ->name('notifications.destroy');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])
        ->name('notifications.clear-all');
    Route::get('/buyer/orders/{order}', [OrderController::class, 'show'])->name('buyer.orders.show');
});

// Artisan routes
Route::middleware(['auth', 'artisan'])->group(function () {
    Route::get('/artisan/dashboard', [ArtisanController::class, 'dashboard'])->name('artisan.dashboard');
    Route::get('/artisan/products', [ArtisanController::class, 'productlist'])->name('artisan.productlist');
    Route::get('/artisan/products/create', [ArtisanController::class, 'create'])->name('products.create');
    Route::post('/artisan/products', [ArtisanController::class, 'store'])->name('products.store');
    Route::get('/artisan/products/{product}', [ArtisanController::class, 'show'])->name('products.show');
    Route::get('/artisan/products/{product}/edit', [ArtisanController::class, 'edit'])->name('products.edit');
    Route::put('/artisan/products/{product}', [ArtisanController::class, 'update'])->name('products.update');
    Route::delete('/artisan/products/{product}', [ArtisanController::class, 'destroy'])->name('products.destroy');

    Route::get('/artisan/orders/index', [ArtisanController::class, 'index'])->name('artisan.orders.index');
    Route::post('orders/update-status/{order}', [ArtisanController::class, 'updateStatus'])->name('artisan.orders.update-status');
    Route::put('orders/update-status/{order}', [ArtisanController::class, 'updateStatus'])->name('artisan.orders.update-status');
    Route::delete('/artisan/orders/{order}', [ArtisanController::class, 'destroyOrder'])->name('artisan.orders.destroy');

    Route::get('/artisan/customers', [ArtisanController::class, 'customers'])->name('artisan.customers');
    
    Route::resource('categories', CategoryController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';