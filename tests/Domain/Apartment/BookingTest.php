<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Booking;
use App\Domain\Apartment\BookingAccepted;
use App\Domain\Apartment\Period;
use App\Domain\Apartment\RentalType;
use App\Domain\EventChannel\EventChannel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID = 'tenantId';

    private Period $period;
    private EventChannel $eventChannel;

    public function setUp(): void
    {
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->period = new Period(
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('08-01-2022')
        );
    }

    /**
     * @test
     */
    public function shouldOpenApartmentBookingWithAllRequiredInformation()
    {
        $actual = Booking::apartment(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            $this->period
        );

        BookingAssertion::assertThat($actual)
            ->isApartmentBooking()
            ->isOpen()
            ->hasRentalPlaceIdEqualTo(self::RENTAL_PLACE_ID)
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo($this->period->asDays());
    }

    /**
     * @test
     */
    public function shouldOpenHotelRoomBookingWithAllRequiredInformation()
    {
        $actual = $this->givenHotelRoomBooking();

        BookingAssertion::assertThat($actual)
            ->isHotelRoomBooking()
            ->isOpen()
            ->hasRentalPlaceIdEqualTo(self::RENTAL_PLACE_ID)
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo($this->period->asDays());
    }

    /**
     * @test
     */
    public function shouldRejectBooking()
    {
        $actual = $this->givenHotelRoomBooking();
        $actual->reject();

        BookingAssertion::assertThat($actual)
            ->isRejected();
    }

    /**
     * @test
     */
    public function shouldAcceptBooking()
    {
        $actual = $this->givenHotelRoomBooking();
        $actual->accept($this->eventChannel);

        BookingAssertion::assertThat($actual)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldPublishBookingAccepted()
    {
        $actual = $this->givenHotelRoomBooking();

        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->will($this->returnCallback(function (BookingAccepted $actual) {
                $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
                $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
                $this->assertEquals(RentalType::HOTEL_ROOM, $actual->getRentalType());
                $this->assertEquals(self::RENTAL_PLACE_ID, $actual->getRentalPlaceId());
                $this->assertEquals(self::TENANT_ID, $actual->getTenantId());
                $this->assertEquals($this->period->asDays(), $actual->getDates());
            }));

        $actual->accept($this->eventChannel);
    }

    public function givenHotelRoomBooking(): Booking
    {
        return Booking::hotelRoom(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            $this->period->asDays()
        );
    }
}
