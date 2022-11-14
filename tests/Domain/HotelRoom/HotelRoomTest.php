<?php

namespace App\Tests\Domain\HotelRoom;

use App\Domain\HotelRoom\HotelRoom;
use App\Domain\HotelRoom\HotelRoomFactory;
use PHPUnit\Framework\TestCase;

class HotelRoomTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateHotelRoomWithAllRequiredFields()
    {
        $hotelId = 'hotelId';
        $number = 1;
        $description = 'description';
        $rooms = [
            'living_room' => 20.0,
            'kitchen' => 10.0,
            'bedroom' => 25.5,
            'bathroom' => 15.2
        ];

        $actual = (new HotelRoomFactory())->create(
            $hotelId,
            $number,
            $description,
            $rooms
        );

        HotelRoomAssertion::assertThat($actual)
            ->hasHotelIdEqualTo($hotelId)
            ->hasNumberEqualTo($number)
            ->hasDescriptionEqualTo($description)
            ->hasNumberOfRooms(count($rooms))
            ->hasRooms($rooms);
    }
}
