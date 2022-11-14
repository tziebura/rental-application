<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\BookingStep;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentBookingAssertion
{
    use PrivatePropertyManipulator;

    private ApartmentBooking $actual;

    public function __construct(ApartmentBooking $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(ApartmentBooking $actual): self
    {
        return new ApartmentBookingAssertion($actual);
    }

    public function hasBookingDateTimeEqualTo(DateTimeImmutable $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'bookingDateTime'));
        return $this;
    }

    public function hasOwnerIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'ownerId'));
        return $this;
    }

    public function hasTenantIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'tenantId'));
        return $this;
    }

    public function hasBookingPeriodThatHas(DateTimeImmutable $expectedStart, DateTimeImmutable $expectedEnd): self
    {
        $bookingPeriod = $this->getByReflection($this->actual, 'bookingPeriod');
        TestCase::assertEquals($expectedStart, $this->getByReflection($bookingPeriod, 'start'));
        TestCase::assertEquals($expectedEnd, $this->getByReflection($bookingPeriod, 'end'));
        return $this;
    }

    public function isStart(): self
    {
        TestCase::assertEquals(BookingStep::START, $this->getByReflection($this->actual, 'step'));
        return $this;
    }
}