<?php

namespace App\Query\Apartment;

class SqlApartmentReadModel implements ApartmentReadModel
{
    private DoctrineOrmApartmentRepository $apartmentRepository;
    private DoctrineOrmApartmentBookingHistoryRepository $apartmentBookingHistoryRepository;

    public function __construct(DoctrineOrmApartmentRepository $apartmentRepository, DoctrineOrmApartmentBookingHistoryRepository $apartmentBookingHistoryRepository)
    {
        $this->apartmentRepository = $apartmentRepository;
        $this->apartmentBookingHistoryRepository = $apartmentBookingHistoryRepository;
    }

    public function findAll(): array
    {
        return $this->apartmentRepository->findAll();
    }

    public function findById(string $id): ?ApartmentDetails
    {
        $apartment = $this->apartmentRepository->find($id);
        $history   = $this->apartmentBookingHistoryRepository->find($id);

        return new ApartmentDetails(
            $apartment,
            $history
        );
    }
}