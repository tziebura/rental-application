<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentFactory;

 class ApartmentApplicationService
{
    public function add(
        string $ownerId, string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city,
        string $country, string $description, array $roomsDefinition
    ): void {
        $factory = new ApartmentFactory();
        $apartment = $factory->create(
            $street, $postalCode, $houseNumber, $apartmentNumber, $city, $country, $roomsDefinition, $ownerId, $description);
    }
}
