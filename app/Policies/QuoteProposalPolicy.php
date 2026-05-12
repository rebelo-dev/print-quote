<?php

namespace App\Policies;

use App\Models\QuoteProposal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuoteProposalPolicy
{
    public function view(User $user, QuoteProposal $quoteProposal): bool
    {
        return $quoteProposal->user_id === $user->id;
    }

    public function update(User $user, QuoteProposal $quoteProposal): bool
    {
        return $quoteProposal->user_id === $user->id;
    }

    public function send(User $user, QuoteProposal $quoteProposal): bool
    {
        return $user->id === $quoteProposal->user_id && $quoteProposal->status === 'draft';
    }

    /*


        
     * Determine whether the user can view any models.
     
    public function viewAny(User $user): bool
    {
        return false;
    }
        
     * Determine whether the user can create models.
     
    public function create(User $user): bool
    {
        return false;
    }

     /**
     * Determine whether the user can delete the model.
    
    public function delete(User $user, QuoteProposal $quoteProposal): bool
    {
        return $quoteProposal->user_id === $user->id;
    }


     * Determine whether the user can restore the model.
     


    public function restore(User $user, QuoteProposal $quoteProposal): bool
    {
        return false;
    } 


  
     * Determine whether the user can permanently delete the model.
     
    public function forceDelete(User $user, QuoteProposal $quoteProposal): bool
    {
        return false;
    }

    */
}
