<?php

namespace App\Infrastructure\Persistence\Sql\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmApartmentBookingHistoryRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ApartmentBookingHistory::class);
    }

    public function save(ApartmentBookingHistory $bookingHistory): void
    {
        $this->_em->persist($bookingHistory);
        $this->_em->flush();
    }
}