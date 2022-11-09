<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Address;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentFactory;
use App\Domain\Apartment\Room;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class ApartmentTest extends TestCase
{
    use PrivatePropertyManipulator;

    /**
     * @test
     */
    public function shouldCreateApartmentWithAllRequiredFields()
    {
        $street = 'street';
        $houseNumber = '1';
        $postalCode = '1-2';
        $apartmentNumber = '1';
        $city = 'city';
        $country = 'country';
        $roomName1 = 'room1';
        $roomSize1 = 10.0;
        $roomName2 = 'room2';
        $roomSize2 = 20.5;

        $roomDefinition = [
            $roomName1 => $roomSize1,
            $roomName2 => $roomSize2,
        ];
        $ownerId = '1';
        $description = 'description';

        $actual = (new ApartmentFactory())->create(
            $street,
            $postalCode,
            $houseNumber,
            $apartmentNumber,
            $city,
            $country,
            $roomDefinition,
            $ownerId,
            $description
        );

        $this->assertHasOwner($ownerId, $actual);
        $this->assertHasDescription($description, $actual);
        $this->assertHasAddress($street, $postalCode, $houseNumber, $apartmentNumber, $city, $country, $actual);
        $this->assertHasRooms($roomDefinition, $actual);
    }

    private function assertHasOwner(string $ownerId, Apartment $actual)
    {
        $this->assertEquals($ownerId, $this->getByReflection($actual, 'ownerId'));
    }

    private function assertHasDescription(string $description, Apartment $actual)
    {
        $this->assertEquals($description, $this->getByReflection($actual, 'description'));
    }

    private function assertHasAddress(string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city, string $country, Apartment $actual)
    {
        $address = new Address(
            $street,
            $postalCode,
            $houseNumber,
            $apartmentNumber,
            $city,
            $country
        );

        $this->assertEquals($address, $this->getByReflection($actual, 'address'));
    }

    private function assertHasRooms(array $roomDefinition, Apartment $actual)
    {
        /** @var Room[] $actualRooms */
        $actualRooms = $this->getByReflection($actual, 'rooms');

        $roomNames = array_keys($roomDefinition);
        $roomSizes = array_values($roomDefinition);

        foreach ($actualRooms as $index => $room) {
            $this->assertEquals($roomNames[$index], $this->getByReflection($room, 'name'));
            $this->assertEquals($roomSizes[$index], $this->getByReflection(
                $this->getByReflection($room, 'squareMeter'),
                'size'
            ));
        }

        $this->assertCount(count($roomDefinition), $actualRooms);
    }

}
