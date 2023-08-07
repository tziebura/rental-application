<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\Period\Period;
use PHPUnit\Framework\TestCase;

class ApartmentBookingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateStartApartmentBookingWithAllRequiredInformation()
    {
        $bookingDateTime = new \DateTimeImmutable('01-01-2022');
        $ownerId = 'ownerId';
        $tenantId = 'tenantId';
        $start = new \DateTimeImmutable('01-01-2022');
        $end = new \DateTimeImmutable('08-01-2022');

        $actual = ApartmentBooking::start(
            $bookingDateTime,
            $ownerId,
            $tenantId,
            Period::of($start, $end)
        );

        ApartmentBookingAssertion::assertThat($actual)
            ->isStart()
            ->hasBookingDateTimeEqualTo($bookingDateTime)
            ->hasOwnerIdEqualTo($ownerId)
            ->hasTenantIdEqualTo($tenantId)
            ->hasBookingPeriodThatHas($start, $end);
    }
}
