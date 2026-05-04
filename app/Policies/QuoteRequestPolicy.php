<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuoteRequest;

class QuoteRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, QuoteRequest $quoteRequest): bool
    {
        return $quoteRequest->user_id === $user->id;
    }

    public function update(User $user, QuoteRequest $quoteRequest): bool
    {
        return $quoteRequest->user_id === $user->id;
    }
}
