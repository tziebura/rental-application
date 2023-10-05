<?php

namespace App\Domain\Apartment;

use App\Domain\Address\AddressDto;

class NewApartmentDto
{
    private string $ownerId;
    private string $street;
    private string $postalCode;
    private string $houseNumber;
    private string $apartmentNumber;
    private string $city;
    private string $country;
    private string $description;

    /**
     * @var array<string, float>
     */
    private array $roomsDefinition;

    public function __construct(
        string $ownerId, string $street, string $postalCode, string $houseNumber, string $apartmentNumber,
        string $city, string $country, string $description, array $roomsDefinition
    ) {
        $this->ownerId = $ownerId;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->houseNumber = $houseNumber;
        $this->apartmentNumber = $apartmentNumber;
        $this->city = $city;
        $this->country = $country;
        $this->description = $description;
        $this->roomsDefinition = $roomsDefinition;
    }

    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function getApartmentNumber(): string
    {
        return $this->apartmentNumber;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string, float>
     */
    public function getRoomsDefinition(): array
    {
        return $this->roomsDefinition;
    }

    public function addressDto(): AddressDto
    {
        return new AddressDto(
            $this->street,
            $this->houseNumber,
            $this->postalCode,
            $this->city,
            $this->country
        );
    }
}