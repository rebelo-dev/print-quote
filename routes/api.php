<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteRequestController;

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
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login',    [AuthController::class, 'login'])->name('auth.login');

// Protected routes, requires authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

    Route::post('/store', [QuoteRequestController::class, 'store'])->name('quote_requests.store');
    Route::get('/index', [QuoteRequestController::class, 'index'])->name('quote_requests.index');
    Route::get('/show', [QuoteRequestController::class, 'show'])->name('quote_requests.show');
    Route::put('/update', [QuoteRequestController::class, 'update'])->name('quote_requests.update');
});

/* 

default example of isolated protected route/ endpoint


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

*/
