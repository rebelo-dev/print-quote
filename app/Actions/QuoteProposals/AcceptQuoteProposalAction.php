<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;
use App\Models\User;

class AcceptQuoteProposalAction
{
    public function execute(QuoteProposal $quoteProposal, User $user): QuoteProposal
    {

        abort_if($quoteProposal->status !== 'sent', 422, 'Only sent proposals can be accepted.');

        // Update the quote proposal status to 'accepted'
        $quoteProposal->status = 'accepted';
        $quoteProposal->accepted_at = now();
        $quoteProposal->save();


        // since the proposal is accepted here we can create a job based on this proposal, next iteration.

        return $quoteProposal;
    }
}
