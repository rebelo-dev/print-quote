<?php

namespace Tests\Unit\Actions;

//use PHPUnit\Framework\TestCase;
use App\Actions\QuoteProposals\CreateQuoteProposalAction;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CreateQuoteProposalActionTest extends TestCase
{
    use RefreshDatabase;
    public function test_creates_proposal_and_marks_request_as_quoted(): void
    {
        $printer = User::factory()->create();
        $quoteRequest = QuoteRequest::factory()->for($printer)->create();

        $action = new CreateQuoteProposalAction();

        $result = $action->execute([
            'title'    => 'Proposta action test',
            'quantity' => 1,
            'price'    => 20.00,
        ], $printer, $quoteRequest);

        $this->assertEquals('Proposta action test', $result->title);
        $this->assertEquals($printer->id, $result->user_id);
        $this->assertEquals($quoteRequest->id, $result->quote_request_id);
        $this->assertEquals('quoted', $quoteRequest->fresh()->status);
    }
}
