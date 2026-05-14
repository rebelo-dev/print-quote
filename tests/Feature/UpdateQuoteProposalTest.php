<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Models\User;

class UpdateQuoteProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_printer_can_update_draft_proposal(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'draft', 'price' => 20.00]);

        $response = $this->actingAs($printer, 'sanctum')
            ->putJson("/api/quote-proposals/{$proposal->id}", [
                'price' => 35.00,
                'notes' => 'Preço actualizado',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_proposals', [
            'id'    => $proposal->id,
            'price' => 35.00,
            'notes' => 'Preço actualizado',
        ]);
    }

    public function test_printer_cannot_update_sent_proposal(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'sent']);

        $response = $this->actingAs($printer, 'sanctum')
            ->putJson("/api/quote-proposals/{$proposal->id}", [
                'price' => 99.00,
            ]);

        $response->assertStatus(422);
    }

    public function test_send_changes_status_to_sent(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'draft']);

        $response = $this->actingAs($printer, 'sanctum')
            ->patchJson("/api/quote-proposals/{$proposal->id}/send");

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_proposals', [
            'id'     => $proposal->id,
            'status' => 'sent',
        ]);
    }

    public function test_accept_creates_job_and_sets_accepted_at(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create([
                'status'   => 'sent',
                'title'    => 'Proposta aceite',
                'price'    => 25.00,
                'quantity' => 1,
            ]);

        $response = $this->actingAs($printer, 'sanctum')
            ->patchJson("/api/quote-proposals/{$proposal->id}/accept");

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_proposals', [
            'id'     => $proposal->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('jobs', [
            'user_id'           => $printer->id,
            'quote_proposal_id' => $proposal->id,
            'price'             => 25.00,
        ]);

        $this->assertNotNull($proposal->fresh()->accepted_at);
    }

    public function test_reject_changes_status_to_rejected(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'sent']);

        $response = $this->actingAs($printer, 'sanctum')
            ->patchJson("/api/quote-proposals/{$proposal->id}/reject");

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_proposals', [
            'id'     => $proposal->id,
            'status' => 'rejected',
        ]);
    }

    public function test_cannot_accept_draft_proposal(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer)->create();
        $proposal = QuoteProposal::factory()
            ->for($printer)
            ->for($quoteRequest)
            ->create(['status' => 'draft']);

        $response = $this->actingAs($printer, 'sanctum')
            ->patchJson("/api/quote-proposals/{$proposal->id}/accept");

        $response->assertStatus(422);
    }
}
