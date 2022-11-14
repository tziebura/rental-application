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
        /** @var Collection $histories */
        $histories = $this->getByReflection($this->actual, 'hotelRoomBookingHistories');

        /** @var HotelRoomBookingHistory $actualHistory */
        $actualHistory = $histories->first();

        /** @var HotelRoomBooking $actualHistory */
        $actualBooking = $this->getByReflection($actualHistory, 'bookings')->first();

        TestCase::assertEquals($expectedHotelRoomId, $actualHistory->getHotelRoomId());
        TestCase::assertEquals($expectedBookingDateTime, $this->getByReflection($actualBooking, 'bookingDateTime'));
        TestCase::assertEquals($expectedTenantId, $this->getByReflection($actualBooking, 'tenantId'));
        TestCase::assertEquals($expectedDays, $this->getByReflection($actualBooking, 'days'));
        TestCase::assertEquals($expectedStep, $this->getByReflection($actualBooking, 'step'));
    }
}