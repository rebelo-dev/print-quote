<?php

namespace Tests\Unit\Actions;

use PHPUnit\Framework\TestCase;

class UpdateQuoteRequestStatusActionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }


    public function test_user_can_update_status(): void
    {
        $user = User::factory()->create();

        $quote = QuoteRequest::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson("/api/quote-requests/{$quote->id}", [
                'status' => 'rejected',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_requests', [
            'id' => $quote->id,
            'status' => 'rejected',
        ]);
    }
}
