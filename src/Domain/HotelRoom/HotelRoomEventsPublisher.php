<?php

namespace App\Domain\HotelRoom;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;

class HotelRoomEventsPublisher
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

    public function publishHotelRoomBooked(int $hotelRoomId, string $hotelId, array $days, string $tenantId): void
    {
        $this->eventChannel->publish(new HotelRoomBooked(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $hotelRoomId,
            $hotelId,
            $days,
            $tenantId
        ));
    }
}