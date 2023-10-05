<?php

namespace App\Infrastructure\Persistence\Sql\Agreement;

use App\Domain\Agreement\Agreement;
use App\Domain\Agreement\AgreementRepository;

class SqlAgreementRepository implements AgreementRepository
{
    private DoctrineOrmAgreementRepository $repository;

    public function __construct(DoctrineOrmAgreementRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(Agreement $agreement): void
    {
        $this->repository->save($agreement);
    }

    public function findById(int $id): ?Agreement
    {
        return $this->repository->find($id);
    }
}