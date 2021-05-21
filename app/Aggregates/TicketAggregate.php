<?php

namespace App\Aggregates;

use App\StorableEvents\TicketEntered;
use App\StorableEvents\TicketPurchased;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TicketAggregate extends AggregateRoot
{
    public function purchaseTicket(string $type)
    {
        $this->recordThat(new TicketPurchased($type));
        return $this;
    }

    public function enter()
    {
        $this->recordThat(new TicketEntered());
        return $this;
    }
}
