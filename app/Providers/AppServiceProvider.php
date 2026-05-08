<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\QuoteRequest;
use App\Policies\QuoteRequestPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\QuoteProposal;
use App\Policies\QuoteProposalPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(QuoteRequest::class, QuoteRequestPolicy::class);
        Gate::policy(QuoteProposal::class, QuoteProposalPolicy::class);
    }
}
