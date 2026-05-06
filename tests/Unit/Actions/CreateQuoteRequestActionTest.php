<?php

namespace Tests\Unit\Actions;

//use PHPUnit\Framework\TestCase;
//use App\Actions\CreateQuoteRequestAction;
use App\Actions\QuoteRequests\CreateQuoteRequestAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateQuoteRequestActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_quote_request_action(): void
    {
        $user = User::factory()->create([
            'slug' => 'printer-1',
        ]);

        $action = new CreateQuoteRequestAction();

        $result = $action->execute([
            'customer_name' => 'Bruno',
            'customer_email' => 'bruno@test.com',
            'title' => 'test',
            'quantity' => 1,
            'slug' => $user->slug,
        ]);

        $this->assertEquals('test', $result->title);
        $this->assertEquals($user->id, $result->user_id);
    }
}
