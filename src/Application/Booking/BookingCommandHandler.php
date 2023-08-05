<?php

namespace App\Application\Booking;

use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\BookingRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingCommandHandler implements EventSubscriberInterface
{
    private BookingRepository $bookingRepository;
    private BookingEventsPublisher $bookingEventsPublisher;

    public function __construct(BookingRepository $bookingRepository, BookingEventsPublisher $bookingEventsPublisher)
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingEventsPublisher = $bookingEventsPublisher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RejectBooking::class => ['reject'],
            AcceptBooking::class => ['accept'],
        ];
    }

    public function reject(RejectBooking $command): void
    {
        $booking = $this->bookingRepository->findById($command->getId());
        $booking->reject();

        $this->bookingRepository->save($booking);
    }

    public function accept(AcceptBooking $command): void
    {
        $booking = $this->bookingRepository->findById($command->getId());
        $booking->accept($this->bookingEventsPublisher);

        $this->bookingRepository->save($booking);
    }
}