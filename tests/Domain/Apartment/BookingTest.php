<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\Booking;
use App\Domain\Apartment\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldOpenApartmentBookingWithAllRequiredInformation()
    {
        $apartmentId = 1;
        $tenantId = 'tenantId';
        $period = new Period(
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('08-01-2022')
        );

        $actual = Booking::apartment(
            $apartmentId,
            $tenantId,
            $period
        );

        BookingAssertion::assertThat($actual)
            ->isApartmentBooking()
            ->isOpen()
            ->hasRentalPlaceIdEqualTo($apartmentId)
            ->hasTenantIdEqualTo($tenantId)
            ->hasDaysEqualTo($period->asDays());
    }

    /**
     * @test
     */
    public function shouldOpenHotelRoomBookingWithAllRequiredInformation()
    {
        $hotelRoomId = 1;
        $tenantId = 'tenantId';
        $period = new Period(
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('08-01-2022')
        );

        $actual = Booking::hotelRoom(
            $hotelRoomId,
            $tenantId,
            $period->asDays()
        );

        BookingAssertion::assertThat($actual)
            ->isHotelRoomBooking()
            ->isOpen()
            ->hasRentalPlaceIdEqualTo($hotelRoomId)
            ->hasTenantIdEqualTo($tenantId)
            ->hasDaysEqualTo($period->asDays());
    }

    /**
     * @test
     */
    public function shouldRejectBooking()
    {
        $hotelRoomId = 1;
        $tenantId = 'tenantId';
        $period = new Period(
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('08-01-2022')
        );

        $actual = Booking::hotelRoom(
            $hotelRoomId,
            $tenantId,
            $period->asDays()
        );

        $actual->reject();

        BookingAssertion::assertThat($actual)
            ->isRejected();
    }
}
