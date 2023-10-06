<?php

namespace App\Domain\Payment;

class PaymentStatus
{
    public const SUCCESS = 'SUCCESS';
    public const NOT_ENOUGH_MONEY = 'NOT_ENOUGH_MONEY';

    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}