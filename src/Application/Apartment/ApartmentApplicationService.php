<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentDomainService;
 use App\Domain\Apartment\ApartmentFactory;
 use App\Domain\Apartment\ApartmentRepository;
 use App\Domain\Booking\BookingRepository;

 class ApartmentApplicationService
{
    private ApartmentRepository $apartmentRepository;
     private BookingRepository $bookingRepository;
     private ApartmentFactory $apartmentFactory;
     private  ApartmentDomainService $apartmentDomainService;

     public function __construct(
         ApartmentRepository $apartmentRepository,
         ApartmentFactory $apartmentFactory,
         BookingRepository $bookingRepository,
         ApartmentDomainService $apartmentDomainService
     ) {
         $this->apartmentRepository = $apartmentRepository;
         $this->bookingRepository = $bookingRepository;
         $this->apartmentFactory = $apartmentFactory;
         $this->apartmentDomainService = $apartmentDomainService;
     }

     public function add(ApartmentDTO $apartmentDTO): void
     {
        $apartment = $this->apartmentFactory->create($apartmentDTO->asNewApartmentDto());
        $this->apartmentRepository->save($apartment);
    }

     public function book(ApartmentBookingDTO $apartmentBookingDTO): void
     {
        $booking = $this->apartmentDomainService->book($apartmentBookingDTO->asNewApartmentBookingDto());
        $this->bookingRepository->save($booking);
     }
 }
