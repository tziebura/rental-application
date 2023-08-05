<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\BookingAccepted;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingEventsPublisherTest extends TestCase
{
    private const RENTAL_TYPE = 'hotel_room';
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID = '2';
    private EventChannel $eventChannel;
    private BookingEventsPublisher $subject;
    /** @var DateTimeImmutable[] */
    private array $dates;

    public function setUp(): void
    {
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->dates = [
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('02-01-2022'),
            new DateTimeImmutable('03-01-2022'),
        ];
        $this->subject = new BookingEventsPublisher(
            new EventIdFactory(),
            new EventCreationTimeFactory(),
            $this->eventChannel
        );
    }

    /**
     * @test
     */
    public function shouldPublishBookingAcceptedEvent(): void
    {
        $this->thenBookingAcceptedEventShouldBePublished();
        $this->subject->publishBookingAccepted(
            self::RENTAL_TYPE,
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            $this->dates
        );
    }

    private function thenBookingAcceptedEventShouldBePublished()
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with($this->callback(function (BookingAccepted $actual) {
                $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
                $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
                $this->assertEquals(self::RENTAL_TYPE, $actual->getRentalType());
                $this->assertEquals(self::RENTAL_PLACE_ID, $actual->getRentalPlaceId());
                $this->assertEquals(self::TENANT_ID, $actual->getTenantId());
                $this->assertEquals($this->dates, $actual->getDates());
                return true;
            }));
    }
}