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

    public function __construct(int $id, string $ownerId, string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city, string $country, string $description, array $rooms)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->houseNumber = $houseNumber;
        $this->apartmentNumber = $apartmentNumber;
        $this->city = $city;
        $this->country = $country;
        $this->description = $description;
        $this->rooms = $rooms;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            $data['owner_id'],
            $data['address_street'],
            $data['address_postal_code'],
            $data['address_house_number'],
            $data['address_apartment_number'],
            $data['address_city'],
            $data['address_country'],
            $data['description'],
            array_map(fn (array $room) => Room::fromArray($room), $data['rooms'])
        );
    }

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