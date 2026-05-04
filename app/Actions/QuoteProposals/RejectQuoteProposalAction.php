<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;

class RejectQuoteProposalAction
{
    public function execute(QuoteProposal $quoteProposal): QuoteProposal
    {

        abort_if($quoteProposal->status !== 'sent', 422, 'Only sent proposals can be rejected.');

        // Update the quote proposal status to 'rejected'
        $quoteProposal->status = 'rejected';
        $quoteProposal->save();

        return $quoteProposal;
    }
}
