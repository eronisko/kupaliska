<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TicketEntered extends ShouldBeStored
{
    public function __construct()
    {
    }
}
