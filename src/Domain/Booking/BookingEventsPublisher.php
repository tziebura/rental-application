<?php

namespace App\Domain\Booking;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;

class BookingEventsPublisher
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

    public function publishBookingAccepted(string $rentalType, int $rentalPlaceId, string $tenantId, array $dates): void
    {
        $this->eventChannel->publish(new BookingAccepted(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $rentalType,
            $rentalPlaceId,
            $tenantId,
            $dates
        ));
    }

    public function publishBookingRejected(string $rentalType, int $rentalPlaceId, string $tenantId, array $dates): void
    {
        $this->eventChannel->publish(new BookingRejected(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $rentalType,
            $rentalPlaceId,
            $tenantId,
            $dates
        ));
    }
}