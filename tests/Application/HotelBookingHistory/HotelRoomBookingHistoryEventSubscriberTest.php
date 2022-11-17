<?php

namespace App\Tests\Application\HotelBookingHistory;

use App\Application\HotelBookingHistory\HotelRoomBookingHistoryEventSubscriber;
use App\Domain\HotelBookingHistory\BookingStep;
use App\Domain\HotelBookingHistory\HotelBookingHistory;
use App\Domain\HotelBookingHistory\HotelBookingHistoryRepository;
use App\Domain\HotelBookingHistory\HotelRoomBooking;
use App\Domain\HotelRoom\HotelRoomBooked;
use App\Tests\Domain\HotelBookingHistory\HotelBookingHistoryAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomBookingHistoryEventSubscriberTest extends TestCase
{
    private const FIRST_BOOKING = 1;
    private const HOTEL_ID = '1';
    const HOTEL_ROOM_ID = 1;
    const TENANT_ID = 'tenantId';

    private HotelBookingHistoryRepository $repository;
    private HotelRoomBookingHistoryEventSubscriber $subject;
    private array $days;
    private HotelBookingHistory $actual;

    public function setUp(): void
    {
        $this->repository = $this->createMock(HotelBookingHistoryRepository::class);
        $this->days = [new DateTimeImmutable('01-01-2022'), new DateTimeImmutable('02-01-2022')];

        $this->subject = new HotelRoomBookingHistoryEventSubscriber($this->repository);
    }

    /**
     * @test
     */
    public function shouldSubscribeEvents()
    {
        $expectedEvents = [
            HotelRoomBooked::class => ['onHotelRoomBooked'],
        ];

        $this->assertEquals($expectedEvents, HotelRoomBookingHistoryEventSubscriber::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldCreateHotelBookingHistoryWhenConsumingHotelRoomBooked()
    {
        $this->givenNotExistingHotelBookingHistory();
        $event = $this->givenHotelRoomBooked();

        $this->thenShouldSaveHotelBookingHistory();
        $this->subject->onHotelRoomBooked($event);

        $this->thenHotelBookingHistoryShouldHave($event->getEventCreationDateTime(), self::FIRST_BOOKING);
    }

    private function givenNotExistingHotelBookingHistory()
    {
        $this->repository->expects($this->once())
            ->method('findFor')
            ->with(self::HOTEL_ID)
            ->willReturn(null);
    }

    /**
     * @test
     */
    public function shouldUpdateExistingApartmentBookingHistoryWhenConsumingApartmentBooked()
    {
        $this->givenExistingHotelBookingHistory();
        $event = $this->givenHotelRoomBooked();

        $this->thenShouldSaveHotelBookingHistory();
        $this->subject->onHotelRoomBooked($event);
        $this->thenHotelBookingHistoryShouldHave($event->getEventCreationDateTime(), self::FIRST_BOOKING);

    }

    private function givenExistingHotelBookingHistory()
    {
        $history = new HotelBookingHistory(self::HOTEL_ID);
        $history->add(
            self::HOTEL_ROOM_ID,
            new DateTimeImmutable(),
            'otherTenantId',
            [new DateTimeImmutable(), (new DateTimeImmutable())->modify('+1days')]
        );

        $this->repository->expects($this->once())
            ->method('findFor')
            ->with(self::HOTEL_ID)
            ->willReturn($history);
    }

    private function givenHotelRoomBooked(): HotelRoomBooked
    {
        return HotelRoomBooked::create(
            self::HOTEL_ROOM_ID,
            self::HOTEL_ID,
            $this->days,
            self::TENANT_ID
        );
    }

    private function thenShouldSaveHotelBookingHistory()
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (HotelBookingHistory $history) {
                $this->actual = $history;
            }));
    }

    private function thenHotelBookingHistoryShouldHave(DateTimeImmutable $expectedBookingDateTime, int $expectedBookingSize)
    {
        HotelBookingHistoryAssertion::assertThat($this->actual)
            ->hasNumberOfEntries($expectedBookingSize)
            ->hasEntryWith(
                self::HOTEL_ROOM_ID,
                $expectedBookingDateTime,
                self::TENANT_ID,
                $this->days,
                BookingStep::START
            );
    }
}
