<?php

namespace App\Infrastructure\Persistence\Sql\Apartment;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentRepository;

class SqlApartmentRepository implements ApartmentRepository
{
    private DoctrineOrmApartmentRepository $doctrineOrmApartmentRepository;

    public function __construct(DoctrineOrmApartmentRepository $doctrineOrmApartmentRepository)
    {
        $this->doctrineOrmApartmentRepository = $doctrineOrmApartmentRepository;
    }

    public function save(Apartment $apartment): void
    {
        $this->doctrineOrmApartmentRepository->save($apartment);
    }

    public function findById(string $id): ?Apartment
    {
        return $this->doctrineOrmApartmentRepository->find($id);
    }

    public function existsById(string $id): bool
    {
        return $this->doctrineOrmApartmentRepository->count(['id' => $id]) > 0;
    }
}