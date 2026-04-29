<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuoteRequestController;
use App\Http\Controllers\QuoteProposalController;

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
//These are for authentication
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login',    [AuthController::class, 'login'])->name('auth.login');

// this is to post a quote-request
Route::post('/quote-requests', [QuoteRequestController::class, 'store'])->name('quote_requests.store');

//this is for the customer to accept/reject a quote proposal
Route::patch('/quote-proposals/{quoteProposal}', [QuoteProposalController::class, 'update'])->name('quote-proposals.update');



// Protected routes, requires authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

    Route::middleware('role:printer')->group(function () {
        Route::get('/quote-requests', [QuoteRequestController::class, 'index'])->name('quote_requests.index');
        Route::get('/quote-requests/{quoteRequest}', [QuoteRequestController::class, 'show'])->name('quote_requests.show');
        Route::patch('/quote-requests/{quoteRequest}', [QuoteRequestController::class, 'update'])->name('quote_requests.update');

        Route::post('/quote-proposals', [QuoteProposalController::class, 'store'])->name('quote-proposals.store');
        Route::get('/quote-proposals', [QuoteProposalController::class, 'index'])->name('quote-proposals.index');
        Route::get('/quote-proposals/{quoteProposal}', [QuoteProposalController::class, 'show'])->name('quote-proposals.show');
        //thinking if i should make a route to see proposals by quote request or maybe customer, same for quote-requests and then jobs, need to look into nested resources maybe, or make custom routes, idk
    });
});



/* 

default example of isolated protected route/ endpoint


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

*/
