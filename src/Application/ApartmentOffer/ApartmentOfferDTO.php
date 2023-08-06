<?php

namespace App\Application\ApartmentOffer;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class ApartmentOfferDTO
{
    /**
     * @Assert\NotBlank()
     */
    private string $apartmentId;

    /**
     * @Assert\NotBlank()
     */
    private float $price;

    /**
     * @Assert\NotBlank()
     */
    private DateTimeImmutable $start;

    /**
     * @Assert\NotBlank()
     */
    private DateTimeImmutable $end;

    public function __construct(string $apartmentId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->apartmentId = $apartmentId;
        $this->price = $price;
        $this->start = $start;
        $this->end = $end;
    }

    public function getApartmentId(): string
    {
        return $this->apartmentId;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }
}