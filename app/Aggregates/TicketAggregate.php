<?php

namespace App\Aggregates;

use App\Exceptions\TicketDenied as TicketDeniedException;
use App\StorableEvents\TicketDenied;
use App\StorableEvents\TicketMarked;
use App\StorableEvents\TicketPurchased;
use App\StorableEvents\TicketScanned;
use Illuminate\Support\Carbon;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TicketAggregate extends AggregateRoot
{
    protected ?string $lastMarkedPool = null;
    protected ?Carbon $lastMarkedAt = null;
    protected int $markedEntries = 0;
    protected string $type = '';

    public function purchaseTicket(string $type)
    {
        $this->recordThat(new TicketPurchased($type));
        return $this;
    }

    public function enter(string $pool)
    {
        $this->recordThat(new TicketScanned($pool, 'enter'));

        if ($this->shouldMarkScan($pool)) {
            if (!$this->ticketHasFreeEntriesAvailable()) {
                $this->recordThat(TicketDenied::noEntriesLeft())->persist();
                throw TicketDeniedException::noEntriesLeft();
            }

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
        $this->markedEntries++;

        $this->lastMarkedPool = $event->pool;
        $this->lastMarkedAt = Carbon::parse($event->at);
    }

    public function applyTicketPurchased(TicketPurchased $event)
    {
        $this->type = $event->type;
    }

    private function shouldMarkScan(string $pool): bool
    {
        if ($this->lastMarkedPool !== $pool) return true;
        if (! $this->lastMarkedAt->isSameDay()) return true;
        return false;
    }

    private function ticketHasFreeEntriesAvailable(): bool
    {
        if ($this->type === 'season') return true;
        if ($this->type === '1_entry') return $this->markedEntries === 0;
        if ($this->type === '10_entries') return $this->markedEntries < 10;
        return false;
    }
}
