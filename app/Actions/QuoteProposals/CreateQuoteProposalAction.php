<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;
use App\Models\User;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class CreateQuoteProposalAction
{
    public function execute(array $data, User $user, QuoteRequest $quoteRequest): QuoteProposal
    {
        // "Make" the quote proposal via relation (auto-injects user_id (printer))
        $quoteProposal = $user->quoteProposals()->make([
            'title'            => $data['title'],
            'description'      => $data['description'] ?? null,
            'estimated_hours'  => $data['estimated_hours'],
            'estimated_weight' => $data['estimated_weight'],
            'quantity'         => $data['quantity'], //the $data is already validated in controller, so there is no need to check if the quantity is null or not, since its required in the validation rules
            'price'            => $data['price'],
            'notes'            => $data['notes'] ?? null,
        ]);

        // Assigning quote request id and material id via direct property assignment
        $quoteProposal->quote_request_id = $quoteRequest->id; // Here , unlike line 21 for quantity, im injecting the id via relation
        $quoteProposal->material_id = $data['material_id'] ?? null; // i should do this via relation as well, otherwise it will not work because material_id is not in the fillable, but since its required in the validation rules, it will always be present in the $data, so i can assign it directly without checking if its null or not 
        $quoteProposal->save(); //saving only the proposal


        // Once the proposal is created, the quote request status is updated to "quoted"
        $quoteRequest->status = 'quoted';
        $quoteRequest->save();

        return $quoteProposal;
    }
}
