<?php

namespace App\Tests\Infrastructure\Fixtures\Hotel;

use App\Domain\Hotel\HotelFactory;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class HotelWithoutRoomsFixture extends AbstractFixture implements ORMFixtureInterface
{
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

        $manager->persist($hotel);
        $manager->flush();
    }
}