<?php

namespace App\Infrastructure\Persistence\Sql\Apartment;

use App\Domain\Apartment\Apartment;
use Doctrine\ORM\EntityRepository;

class DoctrineOrmApartmentRepository extends EntityRepository
{
    public function save(Apartment $apartment): void
    {
        $this->_em->persist($apartment);
        $this->_em->flush();
    }
}