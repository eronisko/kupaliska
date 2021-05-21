<?php

namespace Tests\Feature;

use App\Aggregates\TicketAggregate;
use App\Models\Ticket;
use App\StorableEvents\TicketPurchased;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketEntriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_entries_are_being_counted()
    {
        TicketAggregate::fake()
        ->given([new TicketPurchased('10_entries')])
        ->when(fn (TicketAggregate $aggregate) => $aggregate->uuid())
        ->then(function (string $ticketUuid) {
            $response = $this->postJson('/api/enter', [
                'ticket_uuid' => $ticketUuid,
                'pool' => 'rosnicka'
            ]);

            $response->assertStatus(200);

            $this->assertEquals(1, Ticket::findByUuid($ticketUuid)->marked_entries);
        });
    }

    public function test_does_not_mark_re_entries_to_same_pool()
    {
        TicketAggregate::fake()
        ->given([new TicketPurchased('10_entries')])
        ->when(fn (TicketAggregate $aggregate) => $aggregate->uuid())
        ->then(function (string $ticketUuid) {
            $this->postJson('/api/enter', [
                'ticket_uuid' => $ticketUuid,
                'pool' => 'rosnicka'
            ]);
            $this->postJson('/api/exit', [
                'ticket_uuid' => $ticketUuid,
                'pool' => 'rosnicka'
            ]);
            $this->postJson('/api/enter', [
                'ticket_uuid' => $ticketUuid,
                'pool' => 'rosnicka'
            ]);

            $this->assertEquals(1, Ticket::findByUuid($ticketUuid)->marked_entries);
        });
    }
}
