<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models;
use App\Models\Material;
use App\Models\QuoteProposal;
use App\Models\QuoteRequest;
use App\Models\User;

class CreateQuoteProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_printer_can_create_proposal(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $material = Material::factory()->for($printer)->create();
        $quoteRequest = QuoteRequest::factory()->for($printer)->create();

        $response = $this->actingAs($printer, 'sanctum')
            ->postJson('/api/quote-proposals', [
                'quote_request_id' => $quoteRequest->id,
                'material_id'      => $material->id,
                'title'            => 'Proposta teste',
                'estimated_hours'  => 2.5,
                'estimated_weight' => 45.0,
                'quantity'         => 1,
                'price'            => 25.00,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('quote_proposals', [
            'user_id'          => $printer->id,
            'quote_request_id' => $quoteRequest->id,
            'title'            => 'Proposta teste',
            'price'            => 25.00,
        ]);

        // Quote request deve passar a quoted
        $this->assertDatabaseHas('quote_requests', [
            'id'     => $quoteRequest->id,
            'status' => 'quoted',
        ]);
    }

    public function test_cannot_create_proposal_for_another_printers_request(): void
    {
        $printer1 = User::factory()->create();
        $printer2 = User::factory()->create();
        assert($printer2 instanceof User);

        $quoteRequest = QuoteRequest::factory()->for($printer1)->create();
        $material = Material::factory()->for($printer2)->create();

        $response = $this->actingAs($printer2, 'sanctum')
            ->postJson('/api/quote-proposals', [
                'quote_request_id' => $quoteRequest->id,
                'material_id'      => $material->id,
                'title'            => 'Proposta indevida',
                'quantity'         => 1,
                'price'            => 25.00,
            ]);

        $response->assertStatus(403);
    }

    public function test_cannot_create_duplicate_proposal(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);

        $material = Material::factory()->for($printer)->create();
        $quoteRequest = QuoteRequest::factory()->for($printer)->create();

        // Primeira proposta
        $this->actingAs($printer, 'sanctum')
            ->postJson('/api/quote-proposals', [
                'quote_request_id' => $quoteRequest->id,
                'material_id'      => $material->id,
                'title'            => 'Primeira proposta',
                'quantity'         => 1,
                'price'            => 25.00,
            ]);

        // Segunda proposta para o mesmo request
        $response = $this->actingAs($printer, 'sanctum')
            ->postJson('/api/quote-proposals', [
                'quote_request_id' => $quoteRequest->id,
                'material_id'      => $material->id,
                'title'            => 'Segunda proposta',
                'quantity'         => 1,
                'price'            => 30.00,
            ]);

        $response->assertStatus(422);
    }

    public function test_validation_fails_with_missing_required_fields(): void
    {
        $printer = User::factory()->create();
        assert($printer instanceof User);


        $response = $this->actingAs($printer, 'sanctum')
            ->postJson('/api/quote-proposals', []);

        $response->assertStatus(422);
    }
}
