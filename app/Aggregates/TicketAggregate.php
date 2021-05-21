<?php

namespace App\Aggregates;

use App\StorableEvents\TicketMarked;
use App\StorableEvents\TicketPurchased;
use App\StorableEvents\TicketScanned;
use Illuminate\Support\Carbon;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TicketAggregate extends AggregateRoot
{
    protected ?string $lastMarkedPool = null;
    protected ?Carbon $lastMarkedAt = null;

    public function purchaseTicket(string $type)
    {
        $this->recordThat(new TicketPurchased($type));
        return $this;
    }

    public function enter(string $pool)
    {
        $this->recordThat(new TicketScanned($pool, 'enter'));

        if ($this->shouldMarkScan($pool)) {
            $this->recordThat(new TicketMarked($pool));
        }

        return $this;
    }

    public function exit(string $pool)
    {
        $this->recordThat(new TicketScanned($pool, 'exit'));

        // TODO mark when exiting from another pool?
        return $this;
    }

    public function applyTicketMarked(TicketMarked $event)
    {
        $this->lastMarkedPool = $event->pool;
        $this->lastMarkedAt = Carbon::parse($event->at);
    }

    private function shouldMarkScan(string $pool): bool
    {
        if ($this->lastMarkedPool !== $pool) return true;
        if (! $this->lastMarkedAt->isSameDay()) return true;
        return false;
    }
}
