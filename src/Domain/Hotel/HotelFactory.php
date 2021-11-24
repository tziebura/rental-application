<?php

namespace App\Domain\Hotel;

class HotelFactory
{
    public function create(
        string $name, string $street, string $buildingNumber, string $postalCode, string $city, string $country
    ): Hotel {
        $address = new Address(
            $street,
            $buildingNumber,
            $postalCode,
            $city,
            $country
        );
        return new Hotel($name, $address);
    }
}