<?php

namespace App\Infrastructure\Persistence\Sql\Hotel;

use App\Domain\Hotel\Hotel;
use Doctrine\ORM\EntityRepository;

class DoctrineOrmHotelRepository extends EntityRepository
{
    public function save(Hotel $hotel)
    {
        $this->_em->persist($hotel);
        $this->_em->flush();
    }
}