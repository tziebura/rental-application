<?php

namespace App\Domain\Apartment;

use App\Domain\Address\Address;
use stdClass;

class ApartmentBuilder
{
    private stdClass $apartment;

    private function __construct()
    {
        $this->apartment = new stdClass();
    }

    public static function create(): self
    {
        return new self();
    }

    public function withStreet(string $street): self
    {
        $this->apartment->street = $street;
        return $this;
    }

    public function withPostalCode(string $postalCode): self
    {
        $this->apartment->postalCode = $postalCode;
        return $this;
    }

    public function withHouseNumber(string $houseNumber): self
    {
        $this->apartment->houseNumber = $houseNumber;
        return $this;
    }

    public function withApartmentNumber(string $apartmentNumber): self
    {
        $this->apartment->apartmentNumber = $apartmentNumber;
        return $this;
    }

    public function withCity(string $city): self
    {
        $this->apartment->city = $city;
        return $this;
    }

    public function withCountry(string $country): self
    {
        $this->apartment->country = $country;
        return $this;
    }

    public function withRoomsDefinition(array $roomsDefinition): self
    {
        $this->apartment->roomsDefinition = $roomsDefinition;
        return $this;
    }

    public function withOwnerId(string $ownerId): self
    {
        $this->apartment->ownerId = $ownerId;
        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->apartment->description = $description;
        return $this;
    }

    public function build(): Apartment
    {
        return new Apartment(
            $this->apartment->ownerId,
            $this->apartment->apartmentNumber,
            $this->address(),
            $this->apartment->description,
            $this->rooms()
        );
    }

    private function address(): Address
    {
        return new Address(
            $this->apartment->street,
            $this->apartment->postalCode,
            $this->apartment->houseNumber,
            $this->apartment->city,
            $this->apartment->country
        );
    }

    /**
     * @return Room[]
     */
    private function rooms(): array
    {
        return array_map(function (float $size, string $name) {
            return new Room($name, new SquareMeter($size));
        }, $this->apartment->roomsDefinition, array_keys($this->apartment->roomsDefinition));
    }
}