<?php

namespace App\Tests\Domain\HotelBookingHistory;

use App\Domain\HotelBookingHistory\HotelBookingHistory;
use App\Domain\HotelBookingHistory\HotelRoomBooking;
use App\Domain\HotelBookingHistory\HotelRoomBookingHistory;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class HotelBookingHistoryAssertion
{
    use PrivatePropertyManipulator;

    private HotelBookingHistory $actual;

    public function __construct(HotelBookingHistory $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(HotelBookingHistory $actual): self
    {
        return new self($actual);
    }

    public function hasHotelId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'hotelId'));
        return $this;
    }

    public function hasNumberOfEntries(int $expected): self
    {
        TestCase::assertCount($expected, $this->getByReflection($this->actual, 'hotelRoomBookingHistories'));
        return $this;
    }

    public function hasEntryWith(
        int $expectedHotelRoomId, DateTimeImmutable $expectedBookingDateTime,
        string $expectedTenantId, array $expectedDays, string $expectedStep)
    {
        /** @var Collection<int, HotelRoomBookingHistory> $histories */
        $actualHistories = $this->getByReflection($this->actual, 'hotelRoomBookingHistories');

        $found = false;

        /** @var HotelRoomBookingHistory $actualHistory */
        foreach ($actualHistories as $actualHistory) {
            if ($expectedHotelRoomId !== $actualHistory->getHotelRoomId()) {
                continue;
            }

            /** @var Collection<int, HotelRoomBooking> $actualBookings */
            $actualBookings = $this->getByReflection($actualHistory, 'bookings');

            foreach ($actualBookings as $actualBooking) {
                if (
                    $expectedBookingDateTime->getTimestamp() === $this->getByReflection($actualBooking, 'bookingDateTime')->getTimestamp()
                    &&
                    $expectedTenantId === $this->getByReflection($actualBooking, 'tenantId')
                    &&
                    $expectedStep === $this->getByReflection($actualBooking, 'step')
                    &&
                    $expectedDays === $this->getByReflection($actualBooking, 'days')
                ) {
                    $found = true;
                    break 2;
                }
            }
        }

        TestCase::assertTrue($found);
    }
}