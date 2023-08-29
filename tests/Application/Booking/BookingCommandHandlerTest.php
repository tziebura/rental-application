<?php

namespace App\Tests\Application\Booking;

use App\Application\Booking\AcceptBooking;
use App\Application\Booking\BookingCommandHandler;
use App\Application\Booking\RejectBooking;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\BookingRepository;
use App\Domain\Booking\RentalType;
use App\Tests\Domain\Booking\BookingAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingCommandHandlerTest extends TestCase
{
    const BOOKING_ID = '1';
    const RENTAL_TYPE = 'hotel_room';
    const RENTAL_PLACE_ID = 1;
    const TENANT_ID = 'tenantId';

    private BookingRepository $repository;
    private BookingEventsPublisher $bookingEventsPublisher;
    private BookingCommandHandler $subject;
    private Booking $actual;

    public function setUp(): void
    {
        $this->repository = $this->createMock(BookingRepository::class);
        $this->bookingEventsPublisher = $this->createMock(BookingEventsPublisher::class);

        $this->subject = new BookingCommandHandler(
            $this->repository,
            $this->bookingEventsPublisher,
            new BookingDomainService($this->bookingEventsPublisher)
        );
    }

    /**
     * @test
     */
    public function shouldSubscribeToEvents(): void
    {
        $expectedEvents = [
            RejectBooking::class => ['reject'],
            AcceptBooking::class => ['accept'],
        ];

        $this->assertEquals($expectedEvents, BookingCommandHandler::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldRejectBooking(): void
    {
        $this->givenBooking();
        $command = $this->givenRejectBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->reject($command);
        $this->thenBookingShouldBeRejected();
    }

    private function givenRejectBookingCommand(): RejectBooking
    {
        return new RejectBooking(self::BOOKING_ID);
    }

    private function thenBookingShouldBeRejected(): void
    {
        BookingAssertion::assertThat($this->actual)
            ->isRejected();
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenBookingWithCollisionNotFound(): void
    {
        $this->givenBookingsWithoutCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->accept($command);
        $this->thenBookingShouldBeAccepted();
    }

    /**
     * @test
     */
    public function shouldRejectBookingWhenBookingWithCollisionFound(): void
    {
        $this->givenBookingsWithCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->accept($command);
        $this->thenBookingShouldBeRejected();
    }

    private function givenAcceptBookingCommand(): AcceptBooking
    {
        return new AcceptBooking(self::BOOKING_ID);
    }

    private function thenBookingShouldBeAccepted(): void
    {
        BookingAssertion::assertThat($this->actual)
            ->isAccepted();
    }

    private function givenBooking(): void
    {
        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            self::RENTAL_TYPE,
            [
                new DateTimeImmutable('2023-08-24'),
                new DateTimeImmutable('2023-08-25'),
            ]
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(self::BOOKING_ID)
            ->willReturn($booking);
    }

    private function thenShouldSaveBooking(): void
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Booking $booking) {
                $this->actual = $booking;
            }));
    }

    private function givenBookingsWithoutCollision(): void
    {
        $days = [
            new DateTimeImmutable('2023-08-24'),
            new DateTimeImmutable('2023-08-25'),
            new DateTimeImmutable('2023-08-26'),
        ];

        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            self::RENTAL_TYPE,
            $days
        );

        $this->repository->expects($this->once())
            ->method('findAllBy')
            ->with(RentalType::HOTEL_ROOM, self::RENTAL_PLACE_ID)
            ->willReturn([$booking]);
    }

    private function givenBookingsWithCollision(): void
    {
        $days = [
            new DateTimeImmutable('2023-08-24'),
            new DateTimeImmutable('2023-08-25'),
            new DateTimeImmutable('2023-08-26'),
        ];

        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            self::RENTAL_TYPE,
            $days
        );

        $booking->accept($this->bookingEventsPublisher);

        $this->repository->expects($this->once())
            ->method('findAllBy')
            ->with(RentalType::HOTEL_ROOM, self::RENTAL_PLACE_ID)
            ->willReturn([$booking]);
    }
}
