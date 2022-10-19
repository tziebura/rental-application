<?php

namespace App\Infrastructure\Persistence\Sql\HotelBookingHistory;

use App\Domain\HotelBookingHistory\HotelBookingHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmHotelBookingHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelBookingHistory::class);
    }

    public function save(HotelBookingHistory $bookingHistory)
    {
        $this->_em->persist($bookingHistory);
        $this->_em->flush();
    }
}