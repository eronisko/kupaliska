<?php

namespace Tests\Feature;

use App\Aggregates\TicketAggregate;
use App\StorableEvents\TicketAdmitted;
use App\StorableEvents\TicketMarked;
use App\StorableEvents\TicketPurchased;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketAdmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_allows_entry_for_valid_tickets()
    {
        TicketAggregate::fake()
            ->given([
                new TicketPurchased('1_entry'),
            ])
            ->when(fn (TicketAggregate $aggregate) => $aggregate->uuid())
            ->then(function (string $ticketUuid) {
                $response = $this->postJson('/api/enter', [
                    'ticket_uuid' => $ticketUuid,
                    'pool' => 'rosnicka'
                ]);

                $response->assertExactJson(['admit' => true]);
            });
    }

    public function test_does_not_allow_entry_with_used_ticket()
    {
        TicketAggregate::fake()
            ->given([
                new TicketPurchased('1_entry'),
                new TicketMarked('rosnicka')
            ])
            ->when(fn (TicketAggregate $aggregate) => $aggregate->uuid())
            ->then(function (string $ticketUuid) {
                $response = $this->postJson('/api/enter', [
                    'ticket_uuid' => $ticketUuid,
                    'pool' => 'delfin'
                ]);

                $response->assertExactJson([
                    'admit' => false,
                    'message' => 'This ticket has no more entries left',
                ]);
            });
    }

    public function test_does_not_allow_entry_with_unknown_ticket()
    {
        $this->postJson('/api/enter', [
            'ticket_uuid' => '00000000-0000-0000-0000-000000000000',
            'pool' => 'delfin'
        ])->assertExactJson([
            'admit' => false,
            'message' => 'Unrecognized ticket',
        ]);
    }

    public function test_allows_entry_for_same_ticket_same_day()
    {
        TicketAggregate::fake()
            ->given([
                new TicketPurchased('1_entry'),
                new TicketMarked('rosnicka')
            ])
            ->when(fn (TicketAggregate $aggregate) => $aggregate->uuid())
            ->then(function (string $ticketUuid) {
                $response = $this->postJson('/api/enter', [
                    'ticket_uuid' => $ticketUuid,
                    'pool' => 'rosnicka'
                ]);

                $response->assertExactJson(['admit' => true]);
            });
    }

    public function test_records_ticket_admissions()
    {
        TicketAggregate::fake()
            ->given([
                new TicketPurchased('1_entry'),
            ])
            ->when(function (TicketAggregate $aggregate) {
                $aggregate->enter('rosnicka');
            })
            ->assertEventRecorded(new TicketAdmitted('rosnicka'));
    }
}
