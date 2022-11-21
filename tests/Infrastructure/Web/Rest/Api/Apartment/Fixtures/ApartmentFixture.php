<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\Apartment\Fixtures;

use App\Domain\Apartment\ApartmentFactory;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ApartmentFixture extends AbstractFixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $apartment = (new ApartmentFactory())->create(
            "Florianska", "98-765", "12", "34", "Krakow",
            "Poland", ["Room1" => 50.0], "1234", "The greatest apartment"
        );

        $manager->persist($apartment);
        $manager->flush();
    }
}