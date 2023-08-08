<?php

namespace App\Application\ApartmentBookingHistory;

use App\Domain\Apartment\ApartmentBooked;
use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistoryRepository;
use App\Domain\Period\Period;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApartmentBookingHistoryEventSubscriber implements EventSubscriberInterface
{

    private ApartmentBookingHistoryRepository $apartmentBookingHistoryRepository;

    public function __construct(ApartmentBookingHistoryRepository $apartmentBookingHistoryRepository)
    {
        $this->apartmentBookingHistoryRepository = $apartmentBookingHistoryRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApartmentBooked::class => ['onApartmentBooked'],
        ];
    }

    public function onApartmentBooked(ApartmentBooked $event): void
    {
        $apartmentBookingHistory = $this->apartmentBookingHistoryRepository->findFor($event->getId());

        if (!$apartmentBookingHistory) {
            $apartmentBookingHistory = new ApartmentBookingHistory($event->getId());
        }

        $bookingPeriod = Period::of($event->getPeriodStart(), $event->getPeriodEnd());
        $apartmentBookingHistory->addBookingStart(
           $event->getEventCreationDateTime(),
           $event->getOwnerId(),
           $event->getTenantId(),
           $bookingPeriod
        );

        $this->apartmentBookingHistoryRepository->save($apartmentBookingHistory);
    }
}