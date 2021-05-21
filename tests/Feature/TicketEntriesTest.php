<?php

namespace Tests\Feature;

use App\Aggregates\TicketAggregate;
use App\Models\Ticket;
use App\StorableEvents\TicketEntered;
use App\StorableEvents\TicketPurchased;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketEntriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_counts_entries()
    {
        TicketAggregate::fake()
            ->given([new TicketPurchased('10_entries')])
            ->when(function (TicketAggregate $aggregate) {
                $aggregate
                    ->enter()
                    ->enter()
                    ->persist();
                return $aggregate->uuid();
            })
            ->then(function (string $aggregateUuid) {
                $this->assertEquals(2, Ticket::findByUuid($aggregateUuid)->entries);
            });
    }
}
