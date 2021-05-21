<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketPurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_purchase_works()
    {
        $response = $this->postJson('/api/purchase', ['type' => '10_entries']);
        $response
            ->assertStatus(201)
            ->assertJson([
                'uuid' => true,
            ]);

        $this->assertEquals(1, Ticket::count());
    }

    public function test_validates_works()
    {
        $response = $this->postJson('/api/purchase', ['type' => 'bad']);
        $response->assertStatus(422);

        $this->assertEquals(0, Ticket::count());
    }
}
