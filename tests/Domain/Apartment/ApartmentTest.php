<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentBuilder;
use App\Domain\Apartment\ApartmentEventsPublisher;
use App\Domain\Period\Period;
use App\Domain\Space\NotEnoughSpacesException;
use App\Tests\Domain\Booking\BookingAssertion;
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

    private ApartmentEventsPublisher $apartmentEventsPublisher;

    public function setUp(): void
    {
        $this->apartmentEventsPublisher = $this->createMock(ApartmentEventsPublisher::class);
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
            $this->apartmentEventsPublisher
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

        $this->apartmentEventsPublisher->expects($this->once())
            ->method('publishApartmentBooked')
            ->with(
                self::APARTMENT_ID,
                self::OWNER_ID,
                self::TENANT_ID,
                new Period($start, $end)
            );

        $apartment->book(
            self::TENANT_ID,
            $period,
            $this->apartmentEventsPublisher
        );
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenCreatingApartmentWithNoSpaces()
    {
        $this->expectException(NotEnoughSpacesException::class);
        $this->expectExceptionMessage('No spaces provided.');

        ApartmentBuilder::create()
            ->withStreet(self::STREET)
            ->withPostalCode(self::POSTAL_CODE)
            ->withHouseNumber(self::HOUSE_NUMBER)
            ->withApartmentNumber(self::APARTMENT_NUMBER)
            ->withCity(self::CITY)
            ->withCountry(self::COUNTRY)
            ->withOwnerId(self::OWNER_ID)
            ->withDescription(self::DESCRIPTION)
            ->build();
    }

    private function createApartment(): Apartment
    {
        return ApartmentBuilder::create()
            ->withStreet(self::STREET)
            ->withPostalCode(self::POSTAL_CODE)
            ->withHouseNumber(self::HOUSE_NUMBER)
            ->withApartmentNumber(self::APARTMENT_NUMBER)
            ->withCity(self::CITY)
            ->withCountry(self::COUNTRY)
            ->withRoomsDefinition(self::ROOMS_DEFINITION)
            ->withOwnerId(self::OWNER_ID)
            ->withDescription(self::DESCRIPTION)
            ->build();
    }
}
