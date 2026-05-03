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


        /* or ?

                $printer = User::where('slug', $data['slug'])->firstOrFail();
        */


        // Create the quote proposal via relation (auto-injects user_id (printer))
        $quoteProposal = $user->quoteProposals()->create([
            'quote_request_id' => $data['quote_request_id'],
            'material_id'      => $data['material_id'],
            'title'            => $data['title'],
            'description'      => $data['description'] ?? null,
            'estimated_hours'  => $data['estimated_hours'],
            'estimated_weight' => $data['estimated_weight'],
            'quantity'         => $data['quantity'] ?? 1,
            'price'            => $data['price'],
            'notes'            => $data['notes'] ?? null,
        ]);

        $quoteRequest->update(['status' => 'quoted']);

        $quoteProposal->load('quoteRequest', 'material');

        return $quoteProposal;
    }
}
