<?php

namespace App\Domain\Address;

class AddressVerification
{
    private const VALID_STATUS = 'VALID';

    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function isValid(): bool
    {
        return $this->status === self::VALID_STATUS;
    }
}