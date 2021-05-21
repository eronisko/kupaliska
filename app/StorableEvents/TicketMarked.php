<?php

namespace App\StorableEvents;

use Illuminate\Support\Carbon;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketMarked extends ShouldBeStored
{
    public string $pool;
    public int $at;

    public function __construct(string $pool)
    {
        $this->pool = $pool;
        $this->at = Carbon::now()->getTimestamp();
    }
}
