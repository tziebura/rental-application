<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\HotelBookingHistory\BookingStep;
use App\Domain\Period\Period;
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
        $start =  new DateTimeImmutable();
        $end = $start->modify('+2days');
        $bookingPeriod = Period::of(
            $start,
            $end
        );

        $actual->addBookingStart(
            $bookingDateTime,
            $ownerId,
            $tenantId,
            $bookingPeriod
        );

        ApartmentBookingHistoryAssertion::assertThat($actual)
            ->hasApartmentIdEqualTo($apartmentId)
            ->hasNumberOfEntries(1)
            ->hasEntryWith($bookingDateTime, $ownerId, $tenantId, $bookingPeriod, BookingStep::START);
    }
}
