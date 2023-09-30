<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentEventsPublisher;
 use App\Domain\Apartment\ApartmentFactory;
 use App\Domain\Apartment\ApartmentRepository;
 use App\Domain\Booking\BookingRepository;
 use App\Domain\Owner\OwnerRepository;
 use App\Domain\Period\Period;
 use DateTimeImmutable;

 class ApartmentApplicationService
{
    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;
    private BookingRepository $bookingRepository;
     private OwnerRepository $ownerRepository;
     private ApartmentFactory $apartmentFactory;

     public function __construct(
         ApartmentRepository $apartmentRepository,
         ApartmentEventsPublisher $apartmentEventsPublisher,
         ApartmentFactory $apartmentFactory,
         BookingRepository $bookingRepository
     ) {
         $this->apartmentRepository = $apartmentRepository;
         $this->apartmentEventsPublisher = $apartmentEventsPublisher;
         $this->bookingRepository = $bookingRepository;
         $this->apartmentFactory = $apartmentFactory;
     }

     public function add(ApartmentDTO $apartmentDTO): void
     {
        $apartment = $this->apartmentFactory->create($apartmentDTO->asNewApartmentDto());
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
