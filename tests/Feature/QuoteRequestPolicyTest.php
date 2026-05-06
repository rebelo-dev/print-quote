<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\QuoteRequest;


class QuoteRequestPolicyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_quote_request_ownership(): void
    {


        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        assert($user2 instanceof User);
        // this is both to confirm that user2 is an instance of the user model and to let IDE recognize it as such, line above is not necessary for the test to work.

        $quoteRequest = QuoteRequest::factory()
            ->for($user1)
            ->create();

        $this->actingAs($user2, 'sanctum')
            ->getJson("/api/quote-requests/{$quoteRequest->id}")
            ->assertStatus(403); // Forbidden, as user2 is not the owner of the quote request  
    }
}

  
    /*
  
        $this->assertTrue($quoteRequest->user->is($user1));
        $this->assertFalse($quoteRequest->user->is($user2));

        $this->assertTrue($quoteRequest->user_id === $user1->id);
        $this->assertFalse($quoteRequest->user_id === $user2->id);

        // Correção da relação:
        $quoteRequest = QuoteRequest::factory()
            ->for($user1)
            ->create();
        
    */
