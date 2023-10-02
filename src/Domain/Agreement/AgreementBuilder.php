<?php

namespace App\Domain\Agreement;

use App\Domain\Money\Money;

class AgreementBuilder
{
    private string $rentalType;
    private int $rentalPlaceId;
    private string $tenantId;
    private string $ownerId;
    private array $days;
    private Money $price;

    private function __construct() { }

    public static function create(): self
    {
        return new self();
    }

    public function withRentalType(string $rentalType): self
    {
        $this->rentalType = $rentalType;
        return $this;
    }

    public function withRentalPlaceId(int $rentalPlaceId): self
    {
        $this->rentalPlaceId = $rentalPlaceId;
        return $this;
    }

    public function withTenantId(string $tenantId): self
    {
        $this->tenantId = $tenantId;
        return $this;
    }

    public function withOwnerId(string $ownerId): self
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    public function withDays(array $dates): self
    {
        $this->days = $dates;
        return $this;
    }

    public function withPrice(Money $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function build(): Agreement
    {
        return new Agreement(
            $this->rentalType,
            $this->rentalPlaceId,
            $this->tenantId,
            $this->ownerId,
            $this->days,
            $this->price
        );
    }
}