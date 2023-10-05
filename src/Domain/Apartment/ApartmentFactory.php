<?php

namespace App\Domain\Apartment;

use App\Application\Apartment\OwnerNotFoundException;
use App\Domain\Address\AddressCatalogue;
use App\Domain\Address\AddressDto;
use App\Domain\Address\AddressException;
use App\Domain\Owner\OwnerRepository;

class ApartmentFactory
{
    private OwnerRepository $ownerRepository;
    private AddressCatalogue $addressCatalogue;

    public function __construct(OwnerRepository $ownerRepository, AddressCatalogue $addressCatalogue)
    {
        $this->ownerRepository = $ownerRepository;
        $this->addressCatalogue = $addressCatalogue;
    }

    public function create(NewApartmentDto $newApartmentDto): Apartment
    {
        if (!$this->ownerRepository->exists($newApartmentDto->getOwnerId())) {
            throw OwnerNotFoundException::withId($newApartmentDto->getOwnerId());
        }

        if (!$this->addressCatalogue->exists($newApartmentDto->addressDto())) {
            throw AddressException::notExists();
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