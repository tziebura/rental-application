<?php

namespace App\Infrastructure\Persistence\Sql\HotelRoomOffer;

use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;

class SqlHotelRoomOfferRepository implements HotelRoomOfferRepository
{
    private DoctrineOrmHotelRoomOfferRepository $repository;

    public function __construct(DoctrineOrmHotelRoomOfferRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save(HotelRoomOffer $offer): void
    {
        $this->repository->save($offer);
    }
}