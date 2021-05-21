<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketMarked extends ShouldBeStored
{
    public string $pool;

    public function __construct(string $pool)
    {
        $this->pool = $pool;
    }
}
