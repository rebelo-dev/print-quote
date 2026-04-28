<?php

namespace App\Actions\QuoteRequests;

use App\Models\QuoteRequest;

class UpdateQuoteRequestStatusAction
{
    public function execute(QuoteRequest $quoteRequest, string $status): QuoteRequest
    {
        $quoteRequest->status = $status;
        $quoteRequest->save();

        return $quoteRequest;
    }
}
