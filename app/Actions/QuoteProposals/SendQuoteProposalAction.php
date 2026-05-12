<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;

class SendQuoteProposalAction
{
    public function execute(QuoteProposal $quoteProposal): QuoteProposal
    {

        abort_if($quoteProposal->status !== 'draft', 422, 'Only draft proposals can be sent.');

        // Update the quote proposal status to 'sent', next iteration will be to send an email notification to the customer
        $quoteProposal->status = 'sent';
        $quoteProposal->save();

        return $quoteProposal;
    }
}

//need to generate a token for public acceptance/rejection upon trigering this action
