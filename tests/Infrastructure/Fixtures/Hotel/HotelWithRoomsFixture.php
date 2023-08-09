<?php

namespace App\Tests\Infrastructure\Fixtures\Hotel;

use App\Domain\Hotel\HotelFactory;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class HotelWithRoomsFixture extends AbstractFixture implements ORMFixtureInterface
{
    private const ROOM_NUMBER = 1;
    private const DESCRIPTION = 'description';
    private const ROOMS = [
        'living_room' => 20.0,
        'kitchen' => 10.0,
        'bedroom' => 25.5,
        'bathroom' => 15.2
    ];
    private const NAME = 'name';
    private const STREET = 'street';
    private const BUILDING_NUMBER = '1';
    private const POSTAL_CODE = '12-123';
    private const CITY = 'city';
    private const COUNTRY = 'country';

    public function load(ObjectManager $manager)
    {
        $hotel = (new HotelFactory())->create(
            self::NAME,
            self::STREET,
            self::BUILDING_NUMBER,
            self::POSTAL_CODE,
            self::CITY,
            self::COUNTRY
        );

        $hotel->addHotelRoom(
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            self::ROOMS
        );

        $manager->persist($hotel);
        $manager->flush();
    }
}