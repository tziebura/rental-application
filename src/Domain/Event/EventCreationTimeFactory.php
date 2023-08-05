<?php

namespace App\Domain\Event;

use DateTimeImmutable;

class EventCreationTimeFactory
{
    public function create(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}