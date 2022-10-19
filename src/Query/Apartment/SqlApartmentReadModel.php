<?php

namespace App\Query\Apartment;

class SqlApartmentReadModel implements ApartmentReadModel
{
    private DoctrineOrmApartmentRepository $repository;

    public function __construct(DoctrineOrmApartmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}