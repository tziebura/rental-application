<?php

namespace App\Application\Booking;

use App\Domain\Booking\BookingRepository;
use App\Domain\EventChannel\EventChannel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingCommandHandler implements EventSubscriberInterface
{
    private BookingRepository $bookingRepository;
    private EventChannel $eventChannel;

    public function __construct(BookingRepository $bookingRepository, EventChannel $eventChannel)
    {
        $this->bookingRepository = $bookingRepository;
        $this->eventChannel = $eventChannel;
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
        $booking->accept($this->eventChannel);

        $this->bookingRepository->save($booking);
    }
}