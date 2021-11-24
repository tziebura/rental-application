<?php

namespace App\Application\Hotel;

use App\Domain\Hotel\HotelFactory;

class HotelApplicationService
{
    public function add(
        string $name, string $street, string $buildingNumber, string $postalCode, string $city, string $country
    ): void {
        $factory = new HotelFactory();
        $hotel = $factory->create(
            $name, $street, $buildingNumber, $postalCode, $city, $country);
    }
}