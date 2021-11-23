<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentFactory;
 use App\Domain\Apartment\ApartmentRepository;

 class ApartmentApplicationService
{
    private ApartmentRepository $apartmentRepository;

     public function __construct(ApartmentRepository $apartmentRepository)
     {
         $this->apartmentRepository = $apartmentRepository;
     }

     public function add(
        string $ownerId, string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city,
        string $country, string $description, array $roomsDefinition
    ): void {
        $factory = new ApartmentFactory();
        $apartment = $factory->create(
            $street, $postalCode, $houseNumber, $apartmentNumber, $city, $country, $roomsDefinition, $ownerId, $description);

        $this->apartmentRepository->save($apartment);
    }
}
