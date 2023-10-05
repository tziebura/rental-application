<?php

namespace App\Domain\Payment;

use DateTimeImmutable;

class PaymentFailed
{
    private string $eventId;
    private DateTimeImmutable $eventCreationDateTime;
    private string $senderId;
    private string $recipientId;
    private float $amount;

    public function __construct(string $eventId, DateTimeImmutable $eventCreationDateTime, string $senderId, string $recipientId, float $amount)
    {
        $this->eventId = $eventId;
        $this->eventCreationDateTime = $eventCreationDateTime;
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->amount = $amount;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getEventCreationDateTime(): DateTimeImmutable
    {
        return $this->eventCreationDateTime;
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}