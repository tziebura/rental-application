<?php

namespace App\Domain\Hotel;

class Address
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


}