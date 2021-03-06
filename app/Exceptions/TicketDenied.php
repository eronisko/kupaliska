<?php

namespace App\Exceptions;

use DomainException;

class TicketDenied extends DomainException
{
    public static function noEntriesLeft(): self
    {
        return new static("This ticket has no more entries left");
    }

    public static function notRecognized(): self
    {
        return new static("Unrecognized ticket");
    }
}
