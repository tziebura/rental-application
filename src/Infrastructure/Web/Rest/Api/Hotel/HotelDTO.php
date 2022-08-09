<?php

namespace App\Infrastructure\Web\Rest\Api\Hotel;

use Symfony\Component\Validator\Constraints as Assert;

class HotelDTO
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     */
    private string $street;

    /**
     * @Assert\NotBlank()
     */
    private string $postalCode;

    /**
     * @Assert\NotBlank()
     */
    private string $buildingNumber;

    /**
     * @Assert\NotBlank()
     */
    private string $city;

    /**
     * @Assert\NotBlank()
     */
    private string $country;

    public function __construct(
        string $name, string $street, string $postalCode,
        string $buildingNumber, string $city, string $country
    ) {
        $this->name = $name;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->buildingNumber = $buildingNumber;
        $this->city = $city;
        $this->country = $country;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getBuildingNumber(): string
    {
        return $this->buildingNumber;
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