<?php

namespace App\Actions\QuoteRequests;

use App\Models\Customer;
use App\Models\QuoteRequest;
use App\Models\User;

class CreateQuoteRequestAction
{
    public function execute(array $data): QuoteRequest
    {
        // Find the printer by slug
        $printer = User::where('slug', $data['slug'])->firstOrFail();

        // Find or create the customer via printer relation
        $customer = $printer->customers()->firstOrCreate(
            ['email' => $data['customer_email']],
            ['name'  => $data['customer_name']]
        );

        // Create the quote request via relation (auto-injects user_id (printer))
        $quoteRequest = $printer->quoteRequests()->create([
            'customer_name'  => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'title'          => $data['title'],
            'description'    => $data['description'] ?? null,
            'quantity'       => $data['quantity'] ?? 1,
        ]);

        // Assign customer via relation (associate and saves)
        $quoteRequest->customer()->associate($customer); //maybe this could be in the create above, because parameters are the same.
        $quoteRequest->save();

        $quoteRequest->load('customer');

        return $quoteRequest;
    }
}
