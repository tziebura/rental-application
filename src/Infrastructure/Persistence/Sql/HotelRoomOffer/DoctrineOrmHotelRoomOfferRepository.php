<?php

namespace App\Infrastructure\Persistence\Sql\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmHotelRoomOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelRoomOffer::class);
    }

    public function save(HotelRoomOffer $offer): void
    {
        $this->_em->persist($offer);
        $this->_em->flush();
    }
}