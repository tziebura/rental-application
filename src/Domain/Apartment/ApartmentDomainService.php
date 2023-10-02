<?php

namespace App\Domain\Apartment;

use App\Domain\ApartmentOffer\ApartmentOfferException;
use App\Domain\ApartmentOffer\ApartmentOfferNotFoundException;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Domain\Booking\BookingRepository;
use App\Domain\Booking\RentalType;
use App\Domain\Money\Money;
use App\Domain\Tenant\TenantNotFoundException;
use App\Domain\Booking\Booking;
use App\Domain\Period\Period;
use App\Domain\Tenant\TenantRepository;

class ApartmentDomainService
{

    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;
    private TenantRepository $tenantRepository;
    private BookingRepository $bookingRepository;
    private ApartmentOfferRepository $apartmentOfferRepository;

    public function __construct(
        ApartmentRepository $apartmentRepository,
        ApartmentEventsPublisher $apartmentEventsPublisher,
        TenantRepository $tenantRepository,
        BookingRepository $bookingRepository,
        ApartmentOfferRepository $apartmentOfferRepository
    ) {
        $this->apartmentRepository = $apartmentRepository;
        $this->apartmentEventsPublisher = $apartmentEventsPublisher;
        $this->tenantRepository = $tenantRepository;
        $this->bookingRepository = $bookingRepository;
        $this->apartmentOfferRepository = $apartmentOfferRepository;
    }

    public function book(NewApartmentBookingDto $dto): Booking
    {
        $apartment = $this->apartmentRepository->findById($dto->getApartmentId());

        if (!$apartment) {
            throw ApartmentNotFoundException::withId($dto->getApartmentId());
        }

        if (!$this->tenantRepository->exists($dto->getTenantId())) {
            throw TenantNotFoundException::withId($dto->getTenantId());
        }

        $bookings = $this->bookingRepository->findAllAcceptedBy(RentalType::APARTMENT, (int) $dto->getApartmentId());

        $period = Period::of($dto->getStart(), $dto->getEnd());

        foreach ($bookings as $booking) {
            if ($booking->isFor($period)) {
                throw new ApartmentBookingException();
            }
        }

        $offer = $this->apartmentOfferRepository->findForApartment($dto->getApartmentId());

        if (!$offer) {
            throw ApartmentOfferNotFoundException::forApartmentId($dto->getApartmentId());
        }

        if (!$offer->hasAvailabilityWithin($period)) {
            throw ApartmentOfferException::notAvailableBetween($dto->getStart(), $dto->getEnd());
        }

        $apartmentBooking = new ApartmentBooking(
            $dto->getTenantId(),
            $period,
            $offer->getPrice(),
            $this->apartmentEventsPublisher
        );

        return $apartment->book($apartmentBooking);
    }
}