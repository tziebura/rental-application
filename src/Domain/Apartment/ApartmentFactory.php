<?php

namespace App\Domain\Apartment;

use App\Application\Apartment\OwnerNotFoundException;
use App\Domain\Owner\OwnerRepository;

class ApartmentFactory
{
    private OwnerRepository $ownerRepository;

    public function __construct(OwnerRepository $ownerRepository)
    {
        $this->ownerRepository = $ownerRepository;
    }

    public function create(NewApartmentDto $newApartmentDto): Apartment
    {
        if (!$this->ownerRepository->exists($newApartmentDto->getOwnerId())) {
            throw OwnerNotFoundException::withId($newApartmentDto->getOwnerId());
        }

        return ApartmentBuilder::create()
            ->withStreet($newApartmentDto->getStreet())
            ->withPostalCode($newApartmentDto->getPostalCode())
            ->withHouseNumber($newApartmentDto->getHouseNumber())
            ->withApartmentNumber($newApartmentDto->getApartmentNumber())
            ->withCity($newApartmentDto->getCity())
            ->withCountry($newApartmentDto->getCountry())
            ->withRoomsDefinition($newApartmentDto->getRoomsDefinition())
            ->withOwnerId($newApartmentDto->getOwnerId())
            ->withDescription($newApartmentDto->getDescription())
            ->build();
    }
}