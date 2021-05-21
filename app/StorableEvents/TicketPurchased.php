<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketPurchased extends ShouldBeStored
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}
