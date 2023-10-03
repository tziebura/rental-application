<?php

namespace App\Application\Booking;

use App\Domain\Agreement\AgreementRepository;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\BookingRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingCommandHandler implements EventSubscriberInterface
{
    private BookingRepository $bookingRepository;
    private BookingEventsPublisher $bookingEventsPublisher;
    private BookingDomainService $bookingDomainService;
    private AgreementRepository $agreementRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        BookingEventsPublisher $bookingEventsPublisher,
        BookingDomainService $bookingDomainService,
        AgreementRepository $agreementRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingEventsPublisher = $bookingEventsPublisher;
        $this->bookingDomainService = $bookingDomainService;
        $this->agreementRepository = $agreementRepository;
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

        $agreement = $this->bookingDomainService->accept($booking, $bookings);

        $this->bookingRepository->save($booking);

        if ($agreement) {
            $this->agreementRepository->save($agreement);
        }
    }
}