<?php

namespace App\Tests\Domain\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomOfferAssertion
{
    use PrivatePropertyManipulator;

    private HotelRoomOffer $actual;

    public function __construct(HotelRoomOffer $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(HotelRoomOffer $actual): self
    {
        return new self($actual);
    }

    public function hasHotelRoomId(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'hotelRoomId'));
        return $this;
    }

    public function hasPrice(float $expected): self
    {
        $actualPrice = $this->getByReflection($this->actual, 'price');
        TestCase::assertEquals($expected, $this->getByReflection($actualPrice, 'value'));
        return $this;
    }

    public function hasAvailability(DateTimeImmutable $expectedStart, DateTimeImmutable $expectedEnd): self
    {
        $expectedStart = $expectedStart->setTime(0, 0);
        $expectedEnd   = $expectedEnd->setTime(0, 0);
        $actualAvailability = $this->getByReflection($this->actual, 'availability');
        TestCase::assertEquals($expectedStart, $this->getByReflection($actualAvailability, 'start'));
        TestCase::assertEquals($expectedEnd, $this->getByReflection($actualAvailability, 'end'));
        return $this;
    }

    public function hasHotelRoomNumber(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'hotelRoomNumber'));
        return $this;
    }
}