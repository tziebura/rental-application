<?php

namespace App\Infrastructure\Persistence\Sql\Apartment;

use App\Domain\Apartment\Apartment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmApartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Apartment::class);
    }

    public function save(Apartment $apartment): void
    {
        $this->_em->persist($apartment);
        $this->_em->flush();
    }
}