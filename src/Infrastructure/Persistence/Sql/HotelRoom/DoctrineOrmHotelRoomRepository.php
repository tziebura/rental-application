<?php

namespace App\Infrastructure\Persistence\Sql\HotelRoom;

use App\Domain\HotelRoom\HotelRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmHotelRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelRoom::class);
    }

    public function save(HotelRoom $hotelRoom): void
    {
        $this->_em->persist($hotelRoom);
        $this->_em->flush();
    }
}