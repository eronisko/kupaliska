<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketDenied extends ShouldBeStored
{
    public string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public static function noEntriesLeft(): self
    {
        return new static("no_entries_left");
    }
}
