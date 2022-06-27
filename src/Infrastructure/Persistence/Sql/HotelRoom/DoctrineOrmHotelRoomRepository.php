<?php

namespace App\Infrastructure\Persistence\Sql\HotelRoom;

use App\Domain\HotelRoom\HotelRoom;
use Doctrine\ORM\EntityRepository;

class DoctrineOrmHotelRoomRepository extends EntityRepository
{
    public function save(HotelRoom $hotelRoom): void
    {
        $this->_em->persist($hotelRoom);
        $this->_em->flush();
    }
}