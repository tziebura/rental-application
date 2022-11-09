<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\ApartmentBooked;
use App\Domain\Apartment\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentBookedTest extends TestCase
{

    /**
     * @test
     */
    public function shouldCreateEventWithAllInformation()
    {
        $apartmentId = '1';
        $ownerId = '1';
        $tenantId = '1';

        $periodStart = new DateTimeImmutable();
        $periodEnd = new DateTimeImmutable();

        $period = new Period(
            $periodStart,
            $periodEnd
        );
        $actual = ApartmentBooked::create(
            $apartmentId,
            $ownerId,
            $tenantId,
            $period
        );

        $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
        $this->assertEqualsWithDelta($periodStart->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
        $this->assertEquals($apartmentId, $actual->getId());
        $this->assertEquals($ownerId, $actual->getOwnerId());
        $this->assertEquals($tenantId, $actual->getTenantId());
        $this->assertEquals($periodStart, $actual->getPeriodStart());
        $this->assertEquals($periodEnd, $actual->getPeriodEnd());
    }
}
