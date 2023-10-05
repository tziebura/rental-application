<?php

namespace App\Domain\Payment;

interface PaymentService
{
    public function transfer(string $senderId, string $recipientId, float $amount): string;
}