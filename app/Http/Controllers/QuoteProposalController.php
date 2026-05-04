<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Actions\QuoteProposals\CreateQuoteProposalAction;
//use App\Actions\QuoteProposals\UpdateQuoteProposalAction; not yet implemented but ill need it


class QuoteProposalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $proposals = $request->user()
            ->quoteProposals()
            ->with('quoteRequest')
            ->latest()
            ->get();

        return response()->json($proposals);
    }

    public function show(Request $request, QuoteProposal $quoteProposal): JsonResponse
    {
        abort_if($quoteProposal->user_id !== $request->user()->id, 403);
        $quoteProposal->load('quoteRequest', 'material', 'job');
        return response()->json($quoteProposal);
    }

    public function store(Request $request, CreateQuoteProposalAction $action): JsonResponse
    {


        $data = $request->validate([
            'quote_request_id' => 'required|exists:quote_requests,id',
            'material_id'      => 'required|exists:materials,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'estimated_hours'  => 'required|numeric|min:0',
            'estimated_weight' => 'required|numeric|min:0',
            'quantity'         => 'integer|min:1',
            'price'            => 'required|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);
        // Find the quote request
        $quoteRequest = QuoteRequest::findOrFail($data['quote_request_id']);
        abort_if($quoteRequest->user_id !== $request->user()->id, 403, 'Ownership mismatch, you can only create proposals for your own quote requests.');


        $result = $action->execute($data, $request->user());

        return response()->json($result, 201);
    }

    //Updates methods will be done after createaction
}
