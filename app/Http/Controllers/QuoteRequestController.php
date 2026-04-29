<?php

namespace App\Http\Controllers;

use App\Actions\QuoteRequests\CreateQuoteRequestAction;
use App\Actions\QuoteRequests\UpdateQuoteRequestStatusAction;
use App\Models\QuoteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteRequestController extends Controller
{
    // anyone can submit, no auth required
    public function store(Request $request, CreateQuoteRequestAction $action): JsonResponse
    {
        $data = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'quantity'       => 'integer|min:1',
            'slug'           => 'required|string|exists:users,slug',
        ]);

        $result = $action->execute($data);

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
        abort_if($quoteRequest->user_id !== $request->user()->id, 403);

        $quoteRequest->load('customer', 'quoteProposal');

        return response()->json($quoteRequest);
    }

    public function update(Request $request, QuoteRequest $quoteRequest, UpdateQuoteRequestStatusAction $action): JsonResponse
    {
        abort_if($quoteRequest->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'status' => 'required|in:rejected',
        ]);

        $result = $action->execute($quoteRequest, $data['status']);

        return response()->json($result);
    }
}


/*
Right now im checking ownership right here in the controller, but in a future iteration, I will be moving this into a policy class

Also, data validation is being done in the controller for now, just like ownership check, in a future iteration, i will be moving this into form request classes

*/
