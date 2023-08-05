<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\ApartmentBooked;
use App\Domain\Apartment\ApartmentEventsPublisher;
use App\Domain\Apartment\Period;
use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentEventsPublisherTest extends TestCase
{
    private const APARTMENT_ID = 1;
    private const OWNER_ID = '1';
    private const TENANT_ID = '1';
    private EventChannel $eventChannel;
    private ApartmentEventsPublisher $subject;
    private DateTimeImmutable $periodStart;
    private DateTimeImmutable $periodEnd;

    public function setUp(): void
    {
        $this->periodStart = new DateTimeImmutable();
        $this->periodEnd   = new DateTimeImmutable();

        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->subject = new ApartmentEventsPublisher(
            new EventIdFactory(),
            new EventCreationTimeFactory(),
            $this->eventChannel,
        );
    }

    /**
     * @test
     */
    public function shouldPublishApartmentBookedEvent(): void
    {
        $this->thenShouldPublishApartmentBookedEvent();
        $this->subject->publishApartmentBooked(
            self::APARTMENT_ID,
            self::OWNER_ID,
            self::TENANT_ID,
            new Period(
                $this->periodStart,
                $this->periodEnd
            )
        );
    }

    private function thenShouldPublishApartmentBookedEvent(): void
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with(
                $this->callback(function (ApartmentBooked $actual) {
                    $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
                    $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
                    $this->assertEquals(self::APARTMENT_ID, $actual->getId());
                    $this->assertEquals(self::OWNER_ID, $actual->getOwnerId());
                    $this->assertEquals(self::TENANT_ID, $actual->getTenantId());
                    $this->assertEquals($this->periodStart, $actual->getPeriodStart());
                    $this->assertEquals($this->periodEnd, $actual->getPeriodEnd());
                    return true;
                })
            );
    }
}