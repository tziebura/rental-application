<?php

namespace App\Domain\Payment;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;

class PaymentEventsPublisher
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

    public function publishPaymentCompleted(string $senderId, string $recipientId, float $totalAmount): void
    {
        $this->eventChannel->publish(new PaymentCompleted(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $senderId,
            $recipientId,
            $totalAmount
        ));
    }

    public function publishPaymentFailed(string $senderId, string $recipientId, float $totalAmount): void
    {
        $this->eventChannel->publish(new PaymentFailed(
            $this->eventIdFactory->create(),
            $this->eventCreationTimeFactory->create(),
            $senderId,
            $recipientId,
            $totalAmount
        ));
    }
}