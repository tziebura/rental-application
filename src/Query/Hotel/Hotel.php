<?php

namespace App\Query\Hotel;

class Hotel
{
    private int $id;
    private string $name;
    private string $street;
    private string $buildingNumber;
    private string $postalCode;
    private string $city;
    private string $country;
    private int $numberOfRooms;

    public function __construct(int $id, string $name, string $street, string $buildingNumber, string $postalCode, string $city, string $country, int $numberOfRooms)
    {
        $this->id = $id;
        $this->name = $name;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->country = $country;
        $this->numberOfRooms = $numberOfRooms;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['name'],
            $data['address_street'],
            $data['address_building_number'],
            $data['address_postal_code'],
            $data['address_city'],
            $data['address_country'],
            (int) $data['number_of_rooms']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function getNumberOfRooms(): int
    {
        return $this->numberOfRooms;
    }
}