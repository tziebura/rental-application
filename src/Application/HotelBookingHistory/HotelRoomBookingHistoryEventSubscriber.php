<?php

namespace App\Application\HotelBookingHistory;

use App\Domain\HotelBookingHistory\HotelBookingHistory;
use App\Domain\HotelRoom\HotelRoomBooked;
use App\Domain\HotelBookingHistory\HotelRoomBooking;
use App\Domain\HotelBookingHistory\HotelRoomBookingHistory;
use App\Domain\HotelBookingHistory\HotelBookingHistoryRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HotelRoomBookingHistoryEventSubscriber implements EventSubscriberInterface
{
    private HotelBookingHistoryRepository $hotelBookingHistoryRepository;

    public function __construct(HotelBookingHistoryRepository $hotelBookingHistoryRepository)
    {
        $this->hotelBookingHistoryRepository = $hotelBookingHistoryRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            HotelRoomBooked::class => ['onHotelRoomBooked'],
        ];
    }

    public function onHotelRoomBooked(HotelRoomBooked $event): void
    {
        $bookingHistory = $this->hotelBookingHistoryRepository->findFor($event->getHotelId());

        if (!$bookingHistory) {
            $bookingHistory = new HotelBookingHistory($event->getHotelId());
        }

        $bookingHistory->add(
            $event->getId(),
            $event->getEventCreationDateTime(),
            $event->getTenantId(),
            $event->getDays()
        );

        $this->hotelBookingHistoryRepository->save($bookingHistory);
    }
}