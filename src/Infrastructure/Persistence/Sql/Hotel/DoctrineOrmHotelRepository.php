<?php

namespace App\Infrastructure\Persistence\Sql\Hotel;

use App\Domain\Hotel\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmHotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    public function save(Hotel $hotel)
    {
        $this->_em->persist($hotel);
        $this->_em->flush();
    }
}