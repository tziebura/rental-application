<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentBooked;
use App\Domain\Apartment\ApartmentFactory;
use App\Domain\Apartment\Period;
use App\Domain\EventChannel\EventChannel;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentTest extends TestCase
{
    use PrivatePropertyManipulator;

    private const APARTMENT_ID = 1;
    private const STREET = 'street';
    private const HOUSE_NUMBER = '1';
    private const POSTAL_CODE = '1-2';
    private const APARTMENT_NUMBER = '1';
    private const CITY = 'city';
    private const COUNTRY = 'country';
    private const ROOMS_DEFINITION = [
        'room1' => 10.0,
        'room2' => 20.5,
    ];
    private const OWNER_ID = '1';
    private const DESCRIPTION = 'description';
    private const TENANT_ID = '1';

    private EventChannel $eventChannel;

    public function setUp(): void
    {
        $this->eventChannel = $this->createMock(EventChannel::class);
    }

    /**
     * @test
     */
    public function shouldCreateApartmentWithAllRequiredFields()
    {
        $actual = $this->createApartment();

        ApartmentAssertion::assertThat($actual)
            ->hasOwnerEqualTo(self::OWNER_ID)
            ->hasDescriptionEqualTo(self::DESCRIPTION)
            ->hasAddressEqualTo(self::STREET, self::POSTAL_CODE, self::HOUSE_NUMBER, self::APARTMENT_NUMBER, self::CITY, self::COUNTRY)
            ->hasRoomsEqualTo(self::ROOMS_DEFINITION);
    }

    /**
     * @test
     */
    public function shouldCreateBookingOnceBooked()
    {
        $period = new Period(
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('03-01-2022')
        );
        $apartment = $this->createApartment();
        $this->setByReflection($apartment, 'id', self::APARTMENT_ID);
        $actual = $apartment->book(
            self::TENANT_ID,
            $period,
            $this->eventChannel
        );

        BookingAssertion::assertThat($actual)
            ->isApartmentBooking()
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo($period->asDays())
            ->hasRentalPlaceIdEqualTo(self::APARTMENT_ID);
    }

    /**
     * @test
     */
    public function shouldPublishApartmentBooked()
    {
        $start = new DateTimeImmutable('01-01-2022');
        $end = new DateTimeImmutable('03-01-2022');

        $period = new Period($start, $end);
        $apartment = $this->createApartment();
        $this->setByReflection($apartment, 'id', self::APARTMENT_ID);

        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->will($this->returnCallback(function (ApartmentBooked $actual) use ($start, $end) {
                $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
                $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
                $this->assertEquals(self::APARTMENT_ID, $actual->getId());
                $this->assertEquals(self::OWNER_ID, $actual->getOwnerId());
                $this->assertEquals(self::TENANT_ID, $actual->getTenantId());
                $this->assertEquals($start, $actual->getPeriodStart());
                $this->assertEquals($end, $actual->getPeriodEnd());
            }));

        $apartment->book(
            self::TENANT_ID,
            $period,
            $this->eventChannel
        );
    }

    private function createApartment(): Apartment
    {
        return (new ApartmentFactory())->create(
            self::STREET,
            self::POSTAL_CODE,
            self::HOUSE_NUMBER,
            self::APARTMENT_NUMBER,
            self::CITY,
            self::COUNTRY,
            self::ROOMS_DEFINITION,
            self::OWNER_ID,
            self::DESCRIPTION
        );
    }
}
