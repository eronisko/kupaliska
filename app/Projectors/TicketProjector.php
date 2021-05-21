<?php

namespace App\Projectors;

use App\Events\TicketPurchased;
use App\Models\Ticket;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TicketProjector extends Projector
{
    public function onTicketPurchased(TicketPurchased $event, string $aggregateUuid)
    {
        Ticket::create([
            'uuid' => $aggregateUuid,
            'type' => $event->type,
        ]);
    }
}
