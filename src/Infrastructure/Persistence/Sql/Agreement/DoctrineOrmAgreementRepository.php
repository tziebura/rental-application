<?php

namespace App\Infrastructure\Persistence\Sql\Agreement;

use App\Domain\Agreement\Agreement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmAgreementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agreement::class);
    }

    public function save(Agreement $agreement): void
    {
        $this->_em->persist($agreement);
        $this->_em->flush();
    }
}