<?php

namespace App\Domain\Address;

class AddressDto
{
    private string $street;
    private string $buildingNumber;
    private string $postalCode;
    private string $city;
    private string $country;

    public function __construct(string $street, string $buildingNumber, string $postalCode, string $city, string $country)
    {
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}