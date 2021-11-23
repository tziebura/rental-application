<?php

namespace App\Domain\Apartment;

class ApartmentFactory
{
    public function create(
        string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city, string $country,
        array $roomsDefinition, string $ownerId, string $description
    ): Apartment {
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

        return new Apartment($ownerId, $address, $description, $rooms);
    }
}