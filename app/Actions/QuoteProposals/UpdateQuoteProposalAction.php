<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;
use App\Models\User;

class UpdateQuoteProposalAction
{
    public function execute(array $data, QuoteProposal $quoteProposal): QuoteProposal
    {
        // Only draft proposals can be edited
        abort_if($quoteProposal->status !== 'draft', 422, 'Only draft proposals can be edited.');

        // fill() grabs an existing instance, in this case, the quoteproposal, and replaces data with new values
        $quoteProposal->fill($data);


        // 2 different ways of doing the same, just different sintax here. I'm attributing the material_id key directly because the material of the job might have to change, as to where the quoterequest and user relations are unchanged


        /*if (array_key_exists('material_id', $data)) {
        $quoteProposal->material_id = $data['material_id'];
         }*/

        if (isset($data['material_id'])) {
            $quoteProposal->material_id = $data['material_id'];
        }

        $quoteProposal->save();
        return $quoteProposal;
    }
}
