<?php

namespace App\Domain\ApartmentOffer;

class ApartmentOffer
{
    private int $id;
    private string $apartmentId;
    private Money $price;
    private ApartmentAvailability $availability;

    public function __construct(string $apartmentId, Money $price, ApartmentAvailability $availability)
    {
        $this->apartmentId = $apartmentId;
        $this->price = $price;
        $this->availability = $availability;
    }

}