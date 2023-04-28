<?php

use App\Http\Controllers\HandleNotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/midtrans/web-notif-hook', [HandleNotificationController::class, 'notificationComming']);
Route::post('/transaction', [OrderController::class, 'store'])->name('transaction.store');
Route::apiResource('/product', ProductController::class);
