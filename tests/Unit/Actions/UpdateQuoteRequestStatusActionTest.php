<?php

namespace Tests\Unit\Actions;

//use PHPUnit\Framework\TestCase;
use App\Actions\QuoteRequests\UpdateQuoteRequestStatusAction;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UpdateQuoteRequestStatusActionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    use RefreshDatabase;

    public function test_user_can_update_status(): void
    {
        $user = User::factory()->create();
        assert($user instanceof User);

        $quoteRequest = QuoteRequest::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->patchJson("/api/quote-requests/{$quoteRequest->id}", [
                'status' => 'rejected',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('quote_requests', [
            'id' => $quoteRequest->id,
            'status' => 'rejected',
        ]);
    }
}
