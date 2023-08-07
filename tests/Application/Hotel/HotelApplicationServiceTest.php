<?php

namespace App\Tests\Application\Hotel;

use App\Application\Hotel\HotelApplicationService;
use App\Domain\Address\Address;
use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelRepository;
use App\Tests\Domain\Hotel\HotelAssertion;
use PHPUnit\Framework\TestCase;

class HotelApplicationServiceTest extends TestCase
{
    private HotelRepository $repository;
    private HotelApplicationService $subject;

    public function setUp(): void
    {
        $this->repository = $this->createMock(HotelRepository::class);
        $this->subject = new HotelApplicationService($this->repository);
    }

    /**
     * @test
     */
    public function shouldAddHotelWithAllInformation()
    {
        $name = 'name';
        $street = 'street';
        $buildingNumber = '1';
        $postalCode = '12-123';
        $city = 'city';
        $country = 'country';

        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Hotel $hotel) use (&$actual) {
                $actual = $hotel;
            }));

        $this->subject->add(
            $name,
            $street,
            $buildingNumber,
            $postalCode,
            $city,
            $country
        );

        $expectedAddress = new Address(
            $street,
            $buildingNumber,
            $postalCode,
            $city,
            $country
        );

        HotelAssertion::assertThat($actual)
            ->hasNameEqualTo($name)
            ->hasAddressEqualTo($expectedAddress);
    }
}
