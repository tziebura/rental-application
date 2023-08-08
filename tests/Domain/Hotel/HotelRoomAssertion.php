<?php

namespace App\Tests\Domain\Hotel;

use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelRoom;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class HotelRoomAssertion
{
    use PrivatePropertyManipulator;

    private HotelRoom $actual;

    public function __construct(HotelRoom $actual)
    {
        $this->actual = $actual;
    }

    public static function assertThat(HotelRoom $actual): self
    {
        return new self($actual);
    }

    public function hasHotelIdEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'hotelId'));
        return $this;
    }

    public function hasNumberEqualTo(int $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'number'));
        return $this;
    }

    public function hasDescriptionEqualTo(string $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'description'));
        return $this;
    }

    public function hasNumberOfRooms(int $expected): self
    {
        TestCase::assertCount($expected, $this->getByReflection($this->actual, 'rooms'));
        return $this;
    }

    public function hasRooms(array $expected): self
    {
        $actualRooms = [];
        foreach ($this->getByReflection($this->actual, 'rooms') as $room) {
            $name = $this->getByReflection($room, 'name');
            $squareMeters = $this->getByReflection($room, 'size');
            $actualSize = $this->getByReflection($squareMeters, 'size');

            $actualRooms[$name] = $actualSize;
        }
        TestCase::assertEquals($expected, $actualRooms);
        return $this;
    }

    public function hasHotelEqualTo(Hotel $expected): self
    {
        TestCase::assertEquals($expected, $this->getByReflection($this->actual, 'hotel'));
        return $this;
    }
}