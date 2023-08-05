<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentBuilder;
 use App\Domain\Apartment\ApartmentEventsPublisher;
 use App\Domain\Apartment\ApartmentRepository;
 use App\Domain\Booking\BookingRepository;
 use App\Domain\Apartment\Period;
 use DateTimeImmutable;

 class ApartmentApplicationService
{
    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;
    private BookingRepository $bookingRepository;

     public function __construct(
         ApartmentRepository $apartmentRepository,
         ApartmentEventsPublisher $apartmentEventsPublisher,
         BookingRepository $bookingRepository
     ) {
         $this->apartmentRepository = $apartmentRepository;
         $this->apartmentEventsPublisher = $apartmentEventsPublisher;
         $this->bookingRepository = $bookingRepository;
     }

     public function add(ApartmentDTO $apartmentDTO): void
     {
        $apartment = ApartmentBuilder::create()
            ->withStreet($apartmentDTO->getStreet())
            ->withPostalCode($apartmentDTO->getPostalCode())
            ->withHouseNumber($apartmentDTO->getHouseNumber())
            ->withApartmentNumber($apartmentDTO->getApartmentNumber())
            ->withCity($apartmentDTO->getCity())
            ->withCountry($apartmentDTO->getCountry())
            ->withRoomsDefinition($apartmentDTO->getRoomsDefinition())
            ->withOwnerId($apartmentDTO->getOwnerId())
            ->withDescription($apartmentDTO->getDescription())
            ->build();

        $this->apartmentRepository->save($apartment);
    }

     public function book(string $id, string $tenantId, DateTimeImmutable $start, DateTimeImmutable $end): void
     {
        $apartment = $this->apartmentRepository->findById($id);
        $period = Period::of($start, $end);

        $booking = $apartment->book($tenantId, $period, $this->apartmentEventsPublisher);

        $this->bookingRepository->save($booking);
     }
 }
