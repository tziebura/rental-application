<?php

namespace App\Domain\Apartment;

use App\Domain\Booking\Booking;
use App\Domain\Period\Period;

class ApartmentDomainService
{

    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;

    public function __construct(ApartmentRepository $apartmentRepository, ApartmentEventsPublisher $apartmentEventsPublisher)
    {
        $this->apartmentRepository = $apartmentRepository;
        $this->apartmentEventsPublisher = $apartmentEventsPublisher;
    }

    public function book(NewApartmentBookingDto $dto): Booking
    {
        $apartment = $this->apartmentRepository->findById($dto->getApartmentId());
        $period = Period::of($dto->getStart(), $dto->getEnd());

        return $apartment->book($dto->getTenantId(), $period, $this->apartmentEventsPublisher);
    }
}