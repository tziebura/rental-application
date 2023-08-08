<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\Apartment\Fixtures;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\ApartmentBookingHistory\ApartmentBookingHistory;
use App\Domain\Period\Period;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class ApartmentBookingHistoryFixture extends AbstractFixture implements ORMFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bookingHistory = new ApartmentBookingHistory(1);
        $bookingHistory->addBookingStart(
            new DateTimeImmutable('01-01-2022'),
            1,
            'tenantId',
            Period::of(new DateTimeImmutable('01-02-2022'), new DateTimeImmutable('02-02-2022'))
        );

        $manager->persist($bookingHistory);
        $manager->flush();
    }
}