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
    // Public access: anyone can submit, no auth required
    public function store(StoreQuoteRequestRequest $request, CreateQuoteRequestAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());
        return response()->json($result, 201);
    }

    // Private access: only authenticated printers can access
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
        return response()->json($quoteRequest, 200);
    }

    public function update(UpdateQuoteRequestRequest $request, QuoteRequest $quoteRequest, UpdateQuoteRequestStatusAction $action): JsonResponse
    {
        $this->authorize('update', $quoteRequest);
        $result = $action->execute($quoteRequest, $request->validated()['status']);
        return response()->json($result);
    }
}

/* 

In a next iteration, I will be improving the JSON responses, I can load relationships as needed and return custom data and 
messages of success, failure, errors, etc.


I can use something like

return response()->json([
    'message' => 'Quote request created successfully',
    'data' => $result
], 201);

or 

something like:

public function show(Request $request, QuoteRequest $quoteRequest): JsonResponse
    {
        $this->authorize('view', $quoteRequest);
        $quoteRequest->load('customer', 'quoteProposal');
        return response()->json($quoteRequest->load('customer', 'quoteProposal'), 200, [], JSON_PRETTY_PRINT);
    }

with errors:
return response()->json([
    'message' => 'Validation failed',
    'errors' => $validator->errors()
], 422);

*/
