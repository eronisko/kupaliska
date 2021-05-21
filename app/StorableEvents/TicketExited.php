<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketExited extends ShouldBeStored
{
    public string $pool;

    public function __construct(string $pool)
    {
        $this->pool = $pool;
    }
}
