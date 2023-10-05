<?php

namespace App\Contracts\AddressVerification;

class AddressVerificationRequest
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

    public function serialize(): string
    {
        $data = [
            'street' => $this->street,
            'building_number' => $this->buildingNumber,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'country' => $this->country,
        ];

        return json_encode($data);
    }
}