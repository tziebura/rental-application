<?php

namespace App\Tests\Application\Booking;

use App\Application\Booking\AcceptBooking;
use App\Application\Booking\BookingCommandHandler;
use App\Application\Booking\RejectBooking;
use App\Domain\Apartment\Booking;
use App\Domain\Apartment\BookingRepository;
use App\Domain\EventChannel\EventChannel;
use App\Tests\Domain\Apartment\BookingAssertion;
use PHPUnit\Framework\TestCase;

class BookingCommandHandlerTest extends TestCase
{
    const BOOKING_ID = '1';

    private BookingRepository $repository;
    private EventChannel $eventChannel;
    private BookingCommandHandler $subject;
    private Booking $actual;

    public function setUp(): void
    {
        $this->repository = $this->createMock(BookingRepository::class);
        $this->eventChannel = $this->createMock(EventChannel::class);

        $this->subject = new BookingCommandHandler(
            $this->repository,
            $this->eventChannel
        );
    }

    /**
     * @test
     */
    public function shouldSubscribeEvents()
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
    public function shouldRejectBooking()
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

    private function thenBookingShouldBeRejected()
    {
        BookingAssertion::assertThat($this->actual)
            ->isRejected();
    }

    /**
     * @test
     */
    public function shouldAcceptBooking()
    {
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->accept($command);
        $this->thenBookingShouldBeAccepted();
    }

    private function givenAcceptBookingCommand()
    {
        return new AcceptBooking(self::BOOKING_ID);
    }

    private function thenBookingShouldBeAccepted()
    {
        BookingAssertion::assertThat($this->actual)
            ->isAccepted();
    }

    private function givenBooking(): void
    {
        $booking = new Booking(
            1,
            'tenantId',
            'hotel_room',
            []
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
}
