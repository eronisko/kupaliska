<?php

namespace App\Aggregates;

use App\Events\TicketPurchased;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TicketAggregate extends AggregateRoot
{
    public function purchaseTicket(string $type)
    {
        $this->recordThat(new TicketPurchased($type));

        return $this;
    }
}
