<?php

namespace App\Actions\QuoteProposals;

use App\Models\QuoteProposal;
use App\Models\User;
use App\Models\Job;

class AcceptQuoteProposalAction
{
    public function execute(QuoteProposal $quoteProposal): QuoteProposal
    {

        abort_if($quoteProposal->status !== 'sent', 422, 'Only sent proposals can be accepted.'); //need to see if im keeping this here or maybe move it into a policy

        // Update the quote proposal status to 'accepted'
        $quoteProposal->status = 'accepted';
        $quoteProposal->accepted_at = now();
        $quoteProposal->save();


        // since the proposal is accepted here we can create a job based on this proposal, next iteration.

        // we create the job via quote_proposal relationship, we can assign values via relation because this data has already been validated in the create proposal action.
        $job = $quoteProposal->job()->make([
            'title' => $quoteProposal->title,
            'description' => $quoteProposal->description,
            'quantity' => $quoteProposal->quantity,
            'price' => $quoteProposal->price,
        ]);

        //field status defaults to pending

        // foreign keys assignment outside of the make method since they are not in the fillable of the job model.

        $job->user_id = $quoteProposal->user_id; // the printer who created the proposal becomes the owner of the job
        $job->quote_proposal_id = $quoteProposal->id; // associate the job
        $job->save();

        return $quoteProposal;
    }
}
