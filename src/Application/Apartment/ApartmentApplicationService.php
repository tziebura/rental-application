<?php

namespace App\Application\Apartment;

 use App\Domain\Apartment\ApartmentFactory;
 use App\Domain\Apartment\ApartmentRepository;
 use App\Domain\Apartment\BookingRepository;
 use App\Domain\Apartment\Period;
 use App\Domain\EventChannel\EventChannel;
 use DateTimeImmutable;

 class ApartmentApplicationService
{
    private ApartmentRepository $apartmentRepository;
    private EventChannel $eventChannel;
    private BookingRepository $bookingRepository;

     public function __construct(ApartmentRepository $apartmentRepository, EventChannel $eventChannel, BookingRepository $bookingRepository)
     {
         $this->apartmentRepository = $apartmentRepository;
         $this->eventChannel = $eventChannel;
         $this->bookingRepository = $bookingRepository;
     }

     public function add(
        string $ownerId, string $street, string $postalCode, string $houseNumber, string $apartmentNumber, string $city,
        string $country, string $description, array $roomsDefinition
    ): void {
        $factory = new ApartmentFactory();
        $apartment = $factory->create(
            $street, $postalCode, $houseNumber, $apartmentNumber, $city, $country, $roomsDefinition, $ownerId, $description);

        $this->apartmentRepository->save($apartment);
    }

     public function book(string $id, string $tenantId, DateTimeImmutable $start, DateTimeImmutable $end): void
     {
        $apartment = $this->apartmentRepository->findById($id);
        $period = Period::of($start, $end);

        $booking = $apartment->book($tenantId, $period, $this->eventChannel);

        $this->bookingRepository->save($booking);
     }
 }
