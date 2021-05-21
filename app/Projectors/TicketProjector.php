<?php

namespace App\Projectors;

use App\Models\Ticket;
use App\StorableEvents\TicketEntered;
use App\StorableEvents\TicketPurchased;
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

    public function onTicketEntered(TicketEntered $event, string $aggregateUuid)
    {
        $ticket = Ticket::findByUuid($aggregateUuid);
        $ticket->entries = $ticket->entries + 1;
        $ticket->save();
    }
}
