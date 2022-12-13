<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\Apartment\Fixtures;

use App\Domain\Apartment\ApartmentBuilder;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ApartmentFixture extends AbstractFixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $apartment = ApartmentBuilder::create()
            ->withStreet("Florianska")
            ->withPostalCode("98-765")
            ->withHouseNumber("12")
            ->withApartmentNumber("34")
            ->withCity("Krakow")
            ->withCountry("Poland")
            ->withRoomsDefinition(["Room1" => 50.0])
            ->withOwnerId("1234")
            ->withDescription("The greatest apartment")
            ->build();

        $manager->persist($apartment);
        $manager->flush();
    }
}