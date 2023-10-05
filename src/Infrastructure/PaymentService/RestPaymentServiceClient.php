<?php

namespace App\Infrastructure\PaymentService;

use App\Domain\Payment\PaymentService;
use App\Domain\Payment\PaymentStatus;

class RestPaymentServiceClient implements PaymentService
{
    public function transfer(string $senderId, string $recipientId, float $amount): string
    {
        return PaymentStatus::SUCCESS;
    }
}