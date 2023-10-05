<?php

namespace App\Domain\Payment;

class PaymentFactory
{
    public function create(string $senderId, string $recipientId, array $days, float $price): Payment
    {
        return new Payment(
            $senderId,
            $recipientId,
            count($days) * $price
        );
    }
}