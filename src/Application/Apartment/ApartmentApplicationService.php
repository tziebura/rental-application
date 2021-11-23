<?php

namespace App\Application\Apartment;

use App\Domain\Apartment\Address;
use App\Domain\Apartment\Room;
use App\Domain\Apartment\SquareMeter;

class ApartmentApplicationService
{
    public function add(
        string $ownerId,
        string $street,
        string $postalCode,
        string $houseNumber,
        string $apartmentNumber,
        string $city,
        string $country,
        string $description,
        array $roomsDefinition
    ): void {
        $address = new Address(
            $street,
            $postalCode,
            $houseNumber,
            $apartmentNumber,
            $city,
            $country
        );
        $rooms = array_map(function (float $size, string $name) {
            return new Room($name, new SquareMeter($size));
        }, $roomsDefinition, array_keys($roomsDefinition));

        $apartment = new Apartment($ownerId, $address, $description, $rooms);
    }
}
