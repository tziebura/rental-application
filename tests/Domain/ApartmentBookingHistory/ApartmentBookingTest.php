<?php

namespace App\Tests\Domain\ApartmentBookingHistory;

use App\Domain\ApartmentBookingHistory\ApartmentBooking;
use App\Domain\Period\Period;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentBookingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateStartApartmentBookingWithAllRequiredInformation()
    {
        $bookingDateTime = new DateTimeImmutable('01-01-2022');
        $ownerId = 'ownerId';
        $tenantId = 'tenantId';
        $start = new DateTimeImmutable();
        $end = $start->modify('+7days');

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
