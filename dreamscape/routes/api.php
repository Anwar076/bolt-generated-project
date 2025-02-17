<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{item}', [ItemController::class, 'show']);

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/trades', [TradeController::class, 'index']);
    Route::post('/trades', [TradeController::class, 'store']);
    Route::put('/trades/{trade}', [TradeController::class, 'update']);
    Route::delete('/trades/{trade}', [TradeController::class, 'destroy']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::post('/admin/users', [AdminController::class, 'createUser']);
        Route::post('/admin/items', [AdminController::class, 'createItem']);
        Route::put('/admin/items/{item}', [AdminController::class, 'updateItem']);
        Route::delete('/admin/items/{item}', [AdminController::class, 'deleteItem']);
        Route::post('/admin/assign-item', [AdminController::class, 'assignItem']);
        Route::get('/admin/item-statistics', [AdminController::class, 'itemStatistics']);
    });
});
