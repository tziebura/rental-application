<?php

namespace App\Tests\Domain\Hotel;

use App\Domain\Hotel\Address;
use App\Domain\Hotel\HotelFactory;
use PHPUnit\Framework\TestCase;

class HotelTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateHotelWithAllRequiredFields()
    {
        $name = 'name';
        $street = 'street';
        $buildingNumber = '1';
        $postalCode = '12-123';
        $city = 'city';
        $country = 'country';

        $actual = (new HotelFactory())->create(
            $name,
            $street,
            $buildingNumber,
            $postalCode,
            $city,
            $country
        );

        $address = new Address(
            $street,
            $buildingNumber,
            $postalCode,
            $city,
            $country
        );

        HotelAssertion::assertThat($actual)
            ->hasNameEqualTo($name)
            ->hasAddressEqualTo($address);
    }
}
