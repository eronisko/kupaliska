<?php

namespace App\Aggregates;

use App\StorableEvents\TicketEntered;
use App\StorableEvents\TicketExited;
use App\StorableEvents\TicketMarked;
use App\StorableEvents\TicketPurchased;
use App\StorableEvents\TicketScanned;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TicketAggregate extends AggregateRoot
{
    protected string $lastMarkedEntryPool = '';

    public function purchaseTicket(string $type)
    {
        $this->recordThat(new TicketPurchased($type));
        return $this;
    }

    public function enter(string $pool)
    {
        $this->recordThat(new TicketScanned($pool, 'enter'));

        if ($this->lastMarkedEntryPool !== $pool) {
            $this->recordThat(new TicketMarked($pool));
        }

        return $this;
    }

    public function exit(string $pool)
    {
        $this->recordThat(new TicketScanned($pool, 'exit'));

        // TODO mark when exiting from another pool?
        return $this;
    }

    public function applyTicketMarked(TicketMarked $event) {
        $this->lastMarkedEntryPool = $event->pool;
    }
}
