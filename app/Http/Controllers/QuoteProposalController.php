<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Requests\App\Http\Requests\StoreQuoteProposalRequest;
//use App\Requests\App\Http\Requests\UpdateQuoteProposalRequest;
use App\Actions\QuoteProposals\CreateQuoteProposalAction;
//use App\Actions\QuoteProposals\SendQuoteProposalAction;
use App\Actions\QuoteProposals\AcceptQuoteProposalAction;
use App\Http\Requests\StoreQuoteProposalRequest as RequestsStoreQuoteProposalRequest;
use App\Http\Requests\StoreQuoteRequestRequest;

//use App\Actions\QuoteProposals\RejectQuoteProposalAction;
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

    public function show(QuoteProposal $quoteProposal): JsonResponse
    {
        $this->authorize('view', $quoteProposal);
        $quoteProposal->load('quoteRequest', 'material', 'job');
        return response()->json($quoteProposal);
    }

    public function store(StoreQuoteRequestRequest $request, CreateQuoteProposalAction $action, QuoteProposal $quoteProposal): JsonResponse
    {
        $data = $request->validated();
        // Find the quote request
        $quoteRequest = QuoteRequest::findOrFail($data['quote_request_id']);
        $this->authorize('update', [QuoteRequest::class, $quoteRequest]);

        $result = $action->execute($data, $request->user(), $quoteRequest);

        return response()->json($result, 201);
    }


    /*public function accept(QuoteProposal $quoteProposal, AcceptQuoteProposalAction $action): JsonResponse
    {
        $this->authorize('update', $quoteProposal);
        $result = $action->execute($quoteProposal);

        return response()->json($result);
    }

    public function update(Request $request, QuoteProposal $quoteProposal): JsonResponse
    {
        // WIP

        return response()->json(['message' => 'WIP.'], 501);
    }

    */
}
