<?php

namespace App\Http\Controllers;

use App\Actions\QuoteRequests\CreateQuoteRequestAction;
use App\Actions\QuoteRequests\UpdateQuoteRequestStatusAction;
use App\Models\QuoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuoteRequestRequest;
use App\Http\Requests\UpdateQuoteRequestRequest;

class QuoteRequestController extends Controller
{
    // anyone can submit, no auth required
    public function store(StoreQuoteRequestRequest $request, CreateQuoteRequestAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());
        //$quoteRequest->load('customer'); optional line in case i want to return the quote request with the customer relationship loaded 

        return response()->json($result, 201);
    }

    // all below are for authenticated printers
    public function index(Request $request): JsonResponse
    {
        $requests = $request->user()
            ->quoteRequests()
            ->with('customer')
            ->latest()
            ->get();

        return response()->json($requests);
    }

    public function show(Request $request, QuoteRequest $quoteRequest): JsonResponse
    {
        $this->authorize('view', $quoteRequest);
        $quoteRequest->load('customer', 'quoteProposal');
        return response()->json($quoteRequest);
    }

    public function update(UpdateQuoteRequestRequest $request, QuoteRequest $quoteRequest, UpdateQuoteRequestStatusAction $action): JsonResponse
    {
        $this->authorize('update', $quoteRequest);
        $result = $action->execute($quoteRequest, $request->validated()['status']);
        return response()->json($result);
    }
}
