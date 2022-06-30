<?php

namespace App\Infrastructure\Web\Rest\Api\Apartment;

use Symfony\Component\Validator\Constraints as Assert;

class ApartmentDTO
{
    private string $ownerId;

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
    private string $houseNumber;

    /**
     * @Assert\NotBlank()
     */
    private string $apartmentNumber;

    /**
     * @Assert\NotBlank()
     */
    private string $city;

    /**
     * @Assert\NotBlank()
     */
    private string $country;

    /**
     * @Assert\NotBlank()
     */
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
}