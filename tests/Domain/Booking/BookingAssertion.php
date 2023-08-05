<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingStatus;
use App\Domain\Booking\RentalType;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class BookingAssertion
{
    use PrivatePropertyManipulator;

    private Booking $actual;

    public function __construct(Booking $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(Booking $actual): self
    {
        return new self($actual);
    }

    public function isApartmentBooking(): self
    {
        TestCase::assertEquals(RentalType::APARTMENT, $this->getByReflection($this->actual, 'rentalType'));
        return $this;
    }

    public function isOpen(): self
    {
        TestCase::assertEquals(BookingStatus::OPEN, $this->getByReflection($this->actual, 'status'));
        return $this;
    }

    public function hasRentalPlaceIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'rentalPlaceId'));
        return $this;
    }

    public function hasTenantIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'tenantId'));
        return $this;
    }

    public function hasDaysEqualTo(array $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'dates'));
        return $this;
    }

    public function isHotelRoomBooking(): self
    {
        TestCase::assertEquals(RentalType::HOTEL_ROOM, $this->getByReflection($this->actual, 'rentalType'));
        return $this;
    }

    public function isRejected(): self
    {
        TestCase::assertEquals(BookingStatus::REJECTED, $this->getByReflection($this->actual, 'status'));
        return $this;
    }

    public function isAccepted(): self
    {
        TestCase::assertEquals(BookingStatus::ACCEPTED, $this->getByReflection($this->actual, 'status'));
        return $this;
    }
}