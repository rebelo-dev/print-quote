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
        $quoteProposals = $request->user()
            ->quoteProposals()
            ->with('quoteRequest')
            ->latest()
            ->get();

        return response()->json($quoteProposals);
    }

    public function show(Request $request, QuoteProposal $quoteProposal): JsonResponse
    {

        $this->authorize('view', $quoteProposal);
        $quoteProposal->load('quoteRequest', 'material', 'job');
        return response()->json($quoteProposal);
    }

    public function store(Request $request, CreateQuoteProposalAction $action, QuoteProposal $quoteProposal): JsonResponse
    {


        $data = $request->validated();
        // Find the quote request
        $quoteRequest = QuoteRequest::findOrFail($data['quote_request_id']);
        abort_if($quoteRequest->user_id !== $request->user()->id, 403, 'Ownership mismatch, you can only create proposals for your own quote requests.');


        $result = $action->execute($data, $request->user());

        return response()->json($result, 201);
    }

    //Updates methods will be done after createaction
}
