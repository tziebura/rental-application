<?php

namespace App\Domain\Agreement;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;

class AgreementEventsPublisher
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
    public function publishAgreementAccepted(string $rentalType, string $rentalPlaceId, string $ownerId, string $tenantId, float $price, array $days): void
    {
        $this->eventChannel->publish(new AgreementAccepted(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $rentalType,
            $rentalPlaceId,
            $ownerId,
            $tenantId,
            $price,
            $days
        ));
    }
}