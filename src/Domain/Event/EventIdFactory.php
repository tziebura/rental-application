<?php

namespace App\Domain\Event;

use Ramsey\Uuid\Uuid;

class EventIdFactory
{
    public function create(): string
    {
        return Uuid::uuid4()->toString();
    }
}