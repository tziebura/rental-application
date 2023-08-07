<?php

namespace App\Tests\Application\ApartmentBookingHistory;

use App\Application\ApartmentBookingHistory\ApartmentBookingHistoryEventSubscriber;
use App\Domain\Apartment\ApartmentBooked;
use App\Domain\Period\Period;
use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistoryRepository;
use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\HotelBookingHistory\BookingStep;
use App\Tests\Domain\ApartmentBookingHistory\ApartmentBookingHistoryAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentBookingHistoryEventSubscriberTest extends TestCase
{
    private const APARTMENT_ID = 1;
    private const OWNER_ID = 'ownerId';
    private const TENANT_ID = 'tenantId';
    private const FIRST_BOOKING = 1;
    private const TWO_BOOKINGS = 2;

    private ApartmentBookingHistoryRepository $repository;
    private Period $period;
    private ApartmentBookingHistoryEventSubscriber $subject;

    public function setUp(): void
    {
        $start = new DateTimeImmutable('01-01-2022');
        $end = new DateTimeImmutable('03-01-2022');
        $this->period = new Period($start, $end);
        $this->repository = $this->createMock(ApartmentBookingHistoryRepository::class);
        $this->subject = new ApartmentBookingHistoryEventSubscriber($this->repository);
    }

    /**
     * @test
     */
    public function shouldSubscribeApartmentBookedEvent()
    {
        $expected = [
            ApartmentBooked::class => ['onApartmentBooked'],
        ];

        $this->assertEquals($expected, ApartmentBookingHistoryEventSubscriber::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldCreateApartmentBookingHistoryWhenConsumingApartmentBooked()
    {
        $this->givenNotExistingApartmentBookingHistory();

        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (ApartmentBookingHistory $history) use (&$actual) {
                $actual = $history;
            }));

        $event = $this->givenApartmentBooked();
        $this->subject->onApartmentBooked($event);

        $this->thenApartmentBookingHistoryShouldHave($actual, $event->getEventCreationDateTime(), $this->period, self::FIRST_BOOKING);
    }

    private function givenNotExistingApartmentBookingHistory()
    {
        $this->repository->expects($this->once())
            ->method('findFor')
            ->with(self::APARTMENT_ID)
            ->willReturn(null);
    }

    /**
     * @test
     */
    public function shouldUpdateExistingApartmentBookingHistoryWhenConsumingApartmentBooked()
    {
        $this->givenExistingApartmentBookingHistory();

        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (ApartmentBookingHistory $history) use (&$actual) {
                $actual = $history;
            }));

        $event = $this->givenApartmentBooked();
        $this->subject->onApartmentBooked($event);

        $this->thenApartmentBookingHistoryShouldHave($actual, $event->getEventCreationDateTime(), $this->period, self::TWO_BOOKINGS);
    }

    private function givenExistingApartmentBookingHistory()
    {
        $history = new ApartmentBookingHistory(self::APARTMENT_ID);
        $history->add(ApartmentBooking::start(
            new DateTimeImmutable(),
            self::OWNER_ID,
            'otherTenantId',
            Period::of(new DateTimeImmutable(), (new DateTimeImmutable())->modify('+1days'))
        ));

        $this->repository->expects($this->once())
            ->method('findFor')
            ->with(self::APARTMENT_ID)
            ->willReturn($history);
    }

    private function givenApartmentBooked(): ApartmentBooked
    {
        return new ApartmentBooked(
            (new EventIdFactory())->create(),
        (new EventCreationTimeFactory())->create(),
            self::APARTMENT_ID,
            self::OWNER_ID,
            self::TENANT_ID,
            $this->period
        );
    }

    private function thenApartmentBookingHistoryShouldHave(ApartmentBookingHistory $actual, DateTimeImmutable $expectedBookingDateTime, Period $period, int $expectedBookingsSize)
    {
        ApartmentBookingHistoryAssertion::assertThat($actual)
            ->hasNumberOfEntries($expectedBookingsSize)
            ->hasApartmentIdEqualTo(self::APARTMENT_ID)
            ->hasEntryWith(
                $expectedBookingDateTime,
                self::OWNER_ID,
                self::TENANT_ID,
                Period::of($period->getStart(), $period->getEnd()),
                BookingStep::START
            );
    }
}
