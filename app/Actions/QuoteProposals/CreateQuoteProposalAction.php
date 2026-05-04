<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;
use App\Models\User;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class CreateQuoteProposalAction
{
    public function execute(array $data, User $user): QuoteProposal
    {

        // Find the quote request
        $quoteRequest = QuoteRequest::findOrFail($data['quote_request_id']);

        // "Make" the quote proposal via relation (auto-injects user_id (printer))
        $quoteProposal = $user->quoteProposals()->make([
            'title'            => $data['title'],
            'description'      => $data['description'] ?? null,
            'estimated_hours'  => $data['estimated_hours'],
            'estimated_weight' => $data['estimated_weight'],
            'quantity'         => $data['quantity'] ?? 1,
            'price'            => $data['price'],
            'notes'            => $data['notes'] ?? null,
        ]);

        // Assigning quote request id and material id via direct property assignment
        $quoteProposal->quote_request_id = $quoteRequest->id;
        $quoteProposal->material_id = $data['material_id'] ?? null;
        $quoteProposal->save(); //saving only the proposal



        //$quoteRequest->update(['status' => 'quoted']);
        $quoteRequest->status = 'quoted';
        $quoteRequest->save(); // saving the quote request with new status, this is an update to this model - line 36 doesn't work because since status is not in the fillable, its ignored and assigned a null value


        // $quoteProposal->load('quoteRequest', 'material'); In case I want to return the quote proposal with the quote request and material relationships loaded

        return $quoteProposal;
    }
}
