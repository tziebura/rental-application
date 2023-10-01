<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentDomainService;
 use App\Domain\Apartment\ApartmentEventsPublisher;
 use App\Domain\Apartment\ApartmentFactory;
 use App\Domain\Apartment\ApartmentRepository;
 use App\Domain\Booking\BookingRepository;
 use App\Domain\Owner\OwnerRepository;
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

     public function book(ApartmentBookingDTO $apartmentBookingDTO): void
     {
        $booking = (new ApartmentDomainService($this->apartmentRepository, $this->apartmentEventsPublisher))->book($apartmentBookingDTO->asNewApartmentBookingDto());

        $this->bookingRepository->save($booking);
     }
 }
