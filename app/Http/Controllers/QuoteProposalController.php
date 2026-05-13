<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

//MODELS
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;

//DATA VALIDATION
use App\Http\Requests\StoreQuoteProposalRequest;
use App\Http\Requests\UpdateQuoteProposalRequest;

//ACTIONS
use App\Actions\QuoteProposals\CreateQuoteProposalAction;
use App\Actions\QuoteProposals\SendQuoteProposalAction;
use App\Actions\QuoteProposals\AcceptQuoteProposalAction;
use App\Actions\QuoteProposals\RejectQuoteProposalAction;
use App\Actions\QuoteProposals\UpdateQuoteProposalAction;


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

    public function store(StoreQuoteProposalRequest $request, CreateQuoteProposalAction $action): JsonResponse
    {
        $data = $request->validated();
        // Find the quote request
        $quoteRequest = QuoteRequest::findOrFail($data['quote_request_id']);
        $this->authorize('update', $quoteRequest);

        $result = $action->execute($data, $request->user(), $quoteRequest);

        return response()->json($result, 201);
    }

    public function accept(QuoteProposal $quoteProposal, AcceptQuoteProposalAction $action): JsonResponse
    {
        $this->authorize('update', $quoteProposal);
        $result = $action->execute($quoteProposal);

        return response()->json($result);
    }

    public function reject(QuoteProposal $quoteProposal, RejectQuoteProposalAction $action): JsonResponse
    {
        $this->authorize('update', $quoteProposal);
        $result = $action->execute($quoteProposal);

        return response()->json($result);
    }


    public function send(QuoteProposal $quoteProposal, SendQuoteProposalAction $action): JsonResponse
    {
        $this->authorize('update', $quoteProposal);
        $result = $action->execute($quoteProposal);

        return response()->json($result);
    }
    /*
    //WIP
    public function update(Request $request, QuoteProposal $quoteProposal): JsonResponse
    {
        $this->authorize('update', $quoteProposal);

        // is the proposal a draft? 
        if ($quoteProposal->status !== 'draft') {
            return response()->json(['message' => 'Proposals can only be edited in draft mode.'], 422);
        }

        // validation and update
        //$quoteProposal->update($request->all());

        $quoteProposal->fill($request->validated());
        $quoteProposal->save();


        return response()->json($quoteProposal);
    }*/


    public function update(UpdateQuoteProposalRequest $request, UpdateQuoteProposalAction $action, QuoteProposal $quoteProposal): JsonResponse
    {

        $this->authorize('update', $quoteProposal);

        $result = $action->execute($request->validated(), $quoteProposal);

        return response()->json($result, 201);
    }
}
