<?php

namespace App\Tests\Domain\HotelBookingHistory;

use App\Domain\HotelBookingHistory\BookingStep;
use App\Domain\HotelBookingHistory\HotelBookingHistory;
use PHPUnit\Framework\TestCase;

class HotelBookingHistoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAddNewEntryToHistory()
    {
        $hotelId = 'hotelId';
        $actual = new HotelBookingHistory($hotelId);
        $hotelRoomId = 1;
        $bookingDateTime = new \DateTimeImmutable('01-01-2022');
        $tenantId = 'tenantId';
        $days = [
            new \DateTimeImmutable('01-02-2022'),
            new \DateTimeImmutable('02-02-2022'),
            new \DateTimeImmutable('03-02-2022')
        ];

        $actual->add(
            $hotelRoomId,
            $bookingDateTime,
            $tenantId,
            $days
        );

        HotelBookingHistoryAssertion::assertThat($actual)
            ->hasHotelId($hotelId)
            ->hasNumberOfEntries(1)
            ->hasEntryWith($hotelRoomId, $bookingDateTime, $tenantId, $days, BookingStep::START);
    }
}
