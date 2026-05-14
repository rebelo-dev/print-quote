<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Models\User;

class QuoteProposalPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_printer_cannot_view_another_printers_proposal(): void
    {
        $printer1 = User::factory()->create();
        $printer2 = User::factory()->create();
        assert($printer2 instanceof User);
        // this is both to confirm that user2 is an instance of the user model and to let IDE recognize it as such, line above is not necessary for the test to work.


        $quoteRequest = QuoteRequest::factory()->for($printer1)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer1)
            ->for($quoteRequest)
            ->create();

        $this->actingAs($printer2, 'sanctum')
            ->getJson("/api/quote-proposals/{$proposal->id}")
            ->assertStatus(403);
    }

    public function test_printer_cannot_send_another_printers_proposal(): void
    {
        $printer1 = User::factory()->create();
        $printer2 = User::factory()->create();
        assert($printer2 instanceof User);


        $quoteRequest = QuoteRequest::factory()->for($printer1)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer1)
            ->for($quoteRequest)
            ->create();

        $this->actingAs($printer2, 'sanctum')
            ->patchJson("/api/quote-proposals/{$proposal->id}/send")
            ->assertStatus(403);
    }
}
