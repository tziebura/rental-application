<?php

namespace App\Contracts\AddressVerification;

class AddressVerificationResponse
{
    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
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