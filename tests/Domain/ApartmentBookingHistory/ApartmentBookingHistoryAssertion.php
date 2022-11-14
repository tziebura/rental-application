<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\ApartmentBookingHistory\BookingPeriod;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class ApartmentBookingHistoryAssertion
{
    use PrivatePropertyManipulator;

    private ApartmentBookingHistory $actual;

    public function __construct(ApartmentBookingHistory $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(ApartmentBookingHistory $actual): self
    {
        return new self($actual);
    }

    public function hasApartmentIdEqualTo(int $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'apartmentId'));
        return $this;
    }

    public function hasNumberOfEntries(int $expected): self
    {
        TestCase::assertCount($expected, $this->getByReflection($this->actual, 'bookings'));
        return $this;
    }

    public function hasEntryWith(
        DateTimeImmutable $expectedBookingDateTime, string $expectedOwnerId, string $expectedTenantId,
        BookingPeriod $expectedBookingPeriod, string $expectedStep)
    {
        /** @var Collection<int, ApartmentBooking $bookings */
        $bookings = $this->getByReflection($this->actual, 'bookings');

        $actualBooking = $bookings->first();

        TestCase::assertEquals($expectedBookingDateTime, $this->getByReflection($actualBooking, 'bookingDateTime'));
        TestCase::assertEquals($expectedOwnerId, $this->getByReflection($actualBooking, 'ownerId'));
        TestCase::assertEquals($expectedTenantId, $this->getByReflection($actualBooking, 'tenantId'));
        TestCase::assertEquals($expectedBookingPeriod, $this->getByReflection($actualBooking, 'bookingPeriod'));
        TestCase::assertEquals($expectedStep, $this->getByReflection($actualBooking, 'step'));
    }

}