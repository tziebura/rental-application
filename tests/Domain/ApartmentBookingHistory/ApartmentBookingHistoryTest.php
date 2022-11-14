<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\ApartmentBookingHistory\BookingPeriod;
use App\Domain\HotelBookingHistory\BookingStep;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentBookingHistoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddBookingToHistory()
    {
        $apartmentId = 1;
        $actual = new ApartmentBookingHistory($apartmentId);

        $bookingDateTime = new DateTimeImmutable('01-01-2022');
        $ownerId = 'ownerId';
        $tenantId = 'tenantId';
        $bookingPeriod = new BookingPeriod(
            new DateTimeImmutable('01-02-2022'),
            new DateTimeImmutable('03-02-2022'),
        );

        $actual->add(ApartmentBooking::start(
            $bookingDateTime,
            $ownerId,
            $tenantId,
            $bookingPeriod
        ));

        ApartmentBookingHistoryAssertion::assertThat($actual)
            ->hasApartmentIdEqualTo($apartmentId)
            ->hasNumberOfEntries(1)
            ->hasEntryWith($bookingDateTime, $ownerId, $tenantId, $bookingPeriod, BookingStep::START);
    }
}