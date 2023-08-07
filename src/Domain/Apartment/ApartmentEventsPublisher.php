<?php

namespace App\Domain\Apartment;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use App\Domain\Period\Period;

class ApartmentEventsPublisher
{
    private EventIdFactory $eventIdFactory;
    private EventCreationTimeFactory $eventCreationTimeFactory;
    private EventChannel $eventChannel;

    public function __construct(EventIdFactory $eventIdFactory, EventCreationTimeFactory $eventCreationTimeFactory, EventChannel $eventChannel)
    {
        $this->eventIdFactory = $eventIdFactory;
        $this->eventCreationTimeFactory = $eventCreationTimeFactory;
        $this->eventChannel = $eventChannel;
    }

    public function publishApartmentBooked(int $apartmentId, string $ownerId, string $tenantId, Period $period): void
    {
        $this->eventChannel->publish(new ApartmentBooked(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $apartmentId,
            $ownerId,
            $tenantId,
            $period
        ));
    }
}