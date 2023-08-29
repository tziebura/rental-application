<?php

namespace App\Application\Booking;

use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\BookingRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingCommandHandler implements EventSubscriberInterface
{
    private BookingRepository $bookingRepository;
    private BookingEventsPublisher $bookingEventsPublisher;
    private BookingDomainService $bookingDomainService;

    /**
     * @param BookingRepository $bookingRepository
     * @param BookingEventsPublisher $bookingEventsPublisher
     * @param BookingDomainService $bookingDomainService
     */
    public function __construct(
        BookingRepository $bookingRepository,
        BookingEventsPublisher $bookingEventsPublisher,
        BookingDomainService $bookingDomainService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingEventsPublisher = $bookingEventsPublisher;
        $this->bookingDomainService = $bookingDomainService;
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
        $booking->reject($this->bookingEventsPublisher);

        $this->bookingRepository->save($booking);
    }

    public function accept(AcceptBooking $command): void
    {
        $booking  = $this->bookingRepository->findById($command->getId());
        $bookings = $this->bookingRepository->findAllBy($booking->getRentalType(), $booking->getRentalPlaceId());

        $this->bookingDomainService->accept($booking, $bookings);

        $this->bookingRepository->save($booking);
    }
}