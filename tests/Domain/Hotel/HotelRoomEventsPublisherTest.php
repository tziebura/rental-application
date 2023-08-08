<?php

namespace App\Tests\Domain\Hotel;

use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use App\Domain\Hotel\HotelRoomBooked;
use App\Domain\Hotel\HotelEventsPublisher;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomEventsPublisherTest extends TestCase
{
    const HOTEL_ROOM_ID = 1;
    const HOTEL_ID = '2';
    const TENANT_ID = '3';
    private EventChannel $eventChannel;
    private HotelEventsPublisher $subject;
    /** @var DateTimeImmutable[] */
    private array $days;

    public function setUp(): void
    {
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->days = [new DateTimeImmutable('01-01-2022'), new DateTimeImmutable('02-01-2022')];
        $this->subject = new HotelEventsPublisher(
            new EventIdFactory(),
            new EventCreationTimeFactory(),
            $this->eventChannel
        );
    }

    /**
     * @test
     */
    public function shouldPublishHotelRoomBookedEvent(): void
    {
        $this->thenHotelRoomBookedEventShouldBePublished();
        $this->subject->publishHotelRoomBooked(
            self::HOTEL_ROOM_ID,
            self::HOTEL_ID,
            $this->days,
            self::TENANT_ID
        );
    }

    private function thenHotelRoomBookedEventShouldBePublished(): void
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with($this->callback(function (HotelRoomBooked $actual) {
                $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
                $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
                $this->assertEquals(self::HOTEL_ROOM_ID, $actual->getId());
                $this->assertEquals(self::HOTEL_ID, $actual->getHotelId());
                $this->assertEquals(self::TENANT_ID, $actual->getTenantId());
                $this->assertEquals($this->days, $actual->getDays());
                return true;
            }));
    }
}