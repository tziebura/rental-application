<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingAccepted;
use App\Domain\Booking\NotAllowedBookingStatusTransitionException;
use App\Domain\Period\Period;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\RentalType;
use App\Domain\EventChannel\EventChannel;
use App\Tests\Domain\Booking\BookingAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID = 'tenantId';

    private Period $period;
    private BookingEventsPublisher $bookingEventsPublisher;

    public function setUp(): void
    {
        $this->bookingEventsPublisher = $this->createMock(BookingEventsPublisher::class);
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
        $actual->reject($this->bookingEventsPublisher);

        BookingAssertion::assertThat($actual)
            ->isRejected();
    }

    /**
     * @test
     */
    public function shouldAcceptBooking()
    {
        $actual = $this->givenHotelRoomBooking();
        $actual->accept($this->bookingEventsPublisher);

        BookingAssertion::assertThat($actual)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldPublishBookingAccepted()
    {
        $actual = $this->givenHotelRoomBooking();

        $this->bookingEventsPublisher->expects($this->once())
            ->method('publishBookingAccepted')
            ->with(
                RentalType::HOTEL_ROOM,
                self::RENTAL_PLACE_ID,
                self::TENANT_ID,
                $this->period->asDays()
            );

        $actual->accept($this->bookingEventsPublisher);
    }

    /**
     * @test
     */
    public function shouldNotAllowToRejectAlreadyAcceptedBooking(): void
    {
        $actual = $this->givenHotelRoomBooking();
        $actual->accept($this->bookingEventsPublisher);

        $this->expectException(NotAllowedBookingStatusTransitionException::class);
        $this->expectExceptionMessage('Not allowed to transition from ACCEPTED to REJECTED booking.');

        $actual->reject($this->bookingEventsPublisher);
        BookingAssertion::assertThat($actual)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldNotAllowToAcceptAlreadyRejectedBooking(): void
    {
        $actual = $this->givenHotelRoomBooking();
        $actual->reject($this->bookingEventsPublisher);

        $this->expectException(NotAllowedBookingStatusTransitionException::class);
        $this->expectExceptionMessage('Not allowed to transition from REJECTED to ACCEPTED booking.');

        $actual->accept($this->bookingEventsPublisher);
        BookingAssertion::assertThat($actual)
            ->isRejected();
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
