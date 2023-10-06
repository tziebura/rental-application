<?php

namespace App\Contracts\Payment;

class PaymentResponse
{
    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function success(): self
    {
        return new self('SUCCESS');
    }

    public static function notEnoughMoney(): self
    {
        return new self('NOT_ENOUGH_RESOURCES');
    }

    public function serialize(): string
    {
        return json_encode([
            'status' => $this->status,
        ]);
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}