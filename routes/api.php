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
//i should introduce rate limiting here, maybe for all posts actually, but this one is mandatory
Route::post('/quote-requests', [QuoteRequestController::class, 'store'])->name('quote_requests.store');


// Protected routes, requires authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

    Route::middleware('role:printer')->group(function () {
        Route::get('/quote-requests', [QuoteRequestController::class, 'index'])->name('quote_requests.index');
        Route::get('/quote-requests/{quoteRequest}', [QuoteRequestController::class, 'show'])->name('quote_requests.show');
        Route::patch('/quote-requests/{quoteRequest}', [QuoteRequestController::class, 'update'])->name('quote_requests.update');
        /* 
        This patch route atm, is only to reject requests. As the "quoted" status is set,
        when a printer creates a quote proposal from a request. The customer is not notified of the rejection
        because this is meant to serve as a history device for the printer for rejected requests.

        However as quote-proposals are created, the customer should be notified with the real quote by email for acceptance or not.
        So the idea for the future is that if the printer wants to reject a request, they can append a reason in an email to the customer, or ig, or whatsapp, for that effect i'll have to see what
        the best way to handle this is, maybe a new migration with a field for that in the quote_requests table.
        
        */



        //QuoteProposal Routes



        Route::post('/quote-proposals', [QuoteProposalController::class, 'store'])->name('quote-proposals.store');
        Route::get('/quote-proposals', [QuoteProposalController::class, 'index'])->name('quote-proposals.index');
        Route::get('/quote-proposals/{quoteProposal}', [QuoteProposalController::class, 'show'])->name('quote-proposals.show');
        //this is for the printer do edit a proposal after creation, before sending it to the customer to accept/reject a quote proposal
        Route::put('/quote-proposals/{quoteProposal}', [QuoteProposalController::class, 'update'])->name('quote-proposals.update');

        //this route is for the customer, to accept or reject a proposal, update action should handle both cases, or ill make a seperate action for it. I have acceptance tokens for this case since this will be a public route.
        //Route::patch('/quote-proposals/{quoteProposal}/respond', [QuoteProposalController::class, 'respond'])->name('quote-proposals.respond');
        //Route::patch('/quote-proposals/{quoteProposal}', [QuoteProposalController::class, 'update'])->name('quote-proposals.update');

        //thinking if i should make a route to see proposals by quote request or maybe customer, same for quote-requests and then jobs, need to look into nested resources maybe, or make custom routes, idk
    });
});



/* 

default example of isolated protected route/ endpoint


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

*/
