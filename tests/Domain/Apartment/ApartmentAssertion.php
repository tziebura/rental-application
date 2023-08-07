<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Address;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\Room;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class ApartmentAssertion
{
    use PrivatePropertyManipulator;

    private Apartment $actual;

    public function __construct(Apartment $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(Apartment $actual): self
    {
        return new self($actual);
    }

    public function hasOwnerEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'ownerId'));
        return $this;
    }

    public function hasDescriptionEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'description'));
        return $this;
    }

    public function hasAddressEqualTo(string $expectedStreet, string $expectedPostalCode, string $expectedHouseNumber, string $expectedApartmentNumber, string $expectedCity, string $expectedCountry): self
    {
        $address = new Address(
            $expectedStreet,
            $expectedPostalCode,
            $expectedHouseNumber,
            $expectedCity,
            $expectedCountry
        );

        TestCase::assertEquals($address, $this->getByReflection($this->actual, 'address'));
        return $this;
    }

    public function hasRoomsEqualTo(array $expected): self
    {
        /** @var Room[] $actualRooms */
        $actualRooms = $this->getByReflection($this->actual, 'rooms');

        $roomNames = array_keys($expected);
        $roomSizes = array_values($expected);

        foreach ($actualRooms as $index => $room) {
            TestCase::assertEquals($roomNames[$index], $this->getByReflection($room, 'name'));
            TestCase::assertEquals($roomSizes[$index], $this->getByReflection(
                $this->getByReflection($room, 'squareMeter'),
                'size'
            ));
        }

        TestCase::assertCount(count($expected), $actualRooms);
        return $this;
    }
}