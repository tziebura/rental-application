<?php

namespace App\Tests\Domain\HotelRoom;

use App\Domain\HotelRoom\HotelRoomBooked;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomBookedTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateHotelRoomBookedWithAllInformation()
    {
        $id = 1;
        $hotelId = '1';
        $days = [
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('02-01-2022'),
            new DateTimeImmutable('03-01-2022'),
        ];
        $tenantId = 'tenantId';

        $actual = HotelRoomBooked::create(
            $id,
            $hotelId,
            $days,
            $tenantId
        );

        $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
        $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
        $this->assertEquals($id, $actual->getId());
        $this->assertEquals($hotelId, $actual->getHotelId());
        $this->assertEquals($tenantId, $actual->getTenantId());
        $this->assertEquals($days, $actual->getDays());
    }
}
