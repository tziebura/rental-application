<?php

namespace App\Infrastructure\Persistence\Sql\ApartmentOffer;

use App\Domain\ApartmentOffer\ApartmentOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOrmApartmentOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApartmentOffer::class);
    }

    public function save(ApartmentOffer $apartmentOffer)
    {
        $this->_em->persist($apartmentOffer);
        $this->_em->flush();
    }
}