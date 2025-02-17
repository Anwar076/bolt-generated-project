<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/items'); // Redirect to item catalog by default
});

// Authentication routes
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


// Item routes
Route::get('/items', [ItemController::class, 'indexWeb'])->name('items.index');

// Inventory routes
Route::get('/inventory', [InventoryController::class, 'indexWeb'])->middleware('auth')->name('inventory.index');

// Trade routes
Route::get('/trades', [TradeController::class, 'indexWeb'])->middleware('auth')->name('trades.index');
Route::post('/trades', [TradeController::class, 'store'])->middleware('auth')->name('trades.store');
Route::put('/trades/{trade}', [TradeController::class, 'update'])->middleware('auth')->name('trades.update');
Route::delete('/trades/{trade}', [TradeController::class, 'destroy'])->middleware('auth')->name('trades.destroy');

//User routes
Route::get('/user/{user}', [UserController::class, 'show'])->middleware('auth')->name('user.show');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/items/create', [AdminController::class, 'createItemView'])->name('admin.items.create');
    Route::post('/admin/items', [AdminController::class, 'createItem'])->name('admin.items.store');
    Route::get('/admin/items/{item}/edit', [AdminController::class, 'editItemView'])->name('admin.items.edit');
    Route::put('/admin/items/{item}', [AdminController::class, 'updateItem'])->name('admin.items.update');
    Route::delete('/admin/items/{item}', [AdminController::class, 'deleteItem'])->name('admin.items.destroy');
    Route::post('/admin/users', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/assign-item', [AdminController::class, 'assignItem'])->name('admin.assign-item');
    Route::get('/admin/item-statistics', [AdminController::class, 'itemStatisticsWeb'])->name('admin.item-statistics');

});
