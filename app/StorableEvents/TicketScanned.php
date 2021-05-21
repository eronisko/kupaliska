<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketScanned extends ShouldBeStored
{
    public string $pool;
    public string $direction;

    public function __construct(string $pool, string $direction)
    {
        $this->pool = $pool;
        $this->direction = $direction;
    }
}
