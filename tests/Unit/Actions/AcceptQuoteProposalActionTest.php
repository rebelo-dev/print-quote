<?php

namespace Tests\Unit\Actions;

//use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\QuoteProposals\AcceptQuoteProposalAction;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Models\User;
use Tests\TestCase;


class AcceptQuoteProposalActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_accept_creates_job(): void
    {
        $printer = User::factory()->create();
        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create([
                'status'   => 'sent',
                'price'    => 30.00,
                'quantity' => 2,
            ]);

        $action = new AcceptQuoteProposalAction();
        $result = $action->execute($proposal);

        $this->assertEquals('accepted', $result->status);
        $this->assertNotNull($result->accepted_at);
        $this->assertDatabaseHas('jobs', [
            'quote_proposal_id' => $proposal->id,
            'price'             => 30.00,
        ]);
    }

    public function test_cannot_accept_non_sent_proposal(): void
    {
        $printer = User::factory()->create();
        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'draft']);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $action = new AcceptQuoteProposalAction();
        $action->execute($proposal);
    }
}
