<?php

namespace App\Query\Apartment;

class Apartment
{
    private int $id;
    private string $ownerId;
    private string $street;
    private string $postalCode;
    private string $houseNumber;
    private string $apartmentNumber;
    private string $city;
    private string $country;
    private string $description;
    private array $rooms;

    public function getId(): int
    {
        return $this->id;
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

    public function getRooms(): array
    {
        return $this->rooms;
    }
}