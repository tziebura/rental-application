<?php

namespace App\Tests\Domain\Apartment;

use App\Domain\Apartment\BookingAccepted;
use App\Domain\Apartment\RentalType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingAcceptedTest extends TestCase
{

    /**
     * @test
     * @dataProvider getDifferentRentalPlaceTypes
     */
    public function shouldCreateEventWithAllInformation(string $expectedRentalType)
    {
        $rentalPlaceId = 1;
        $tenantId = '1';
        $dates = [
            new DateTimeImmutable('01-01-2022'),
            new DateTimeImmutable('02-01-2022'),
            new DateTimeImmutable('03-01-2022'),
        ];

        $actual = BookingAccepted::create(
            $expectedRentalType,
            $rentalPlaceId,
            $tenantId,
            $dates
        );

        $this->assertMatchesRegularExpression('/[0-9a-z\\-]{36}/', $actual->getEventId());
        $this->assertEqualsWithDelta((new DateTimeImmutable())->getTimestamp(), $actual->getEventCreationDateTime()->getTimestamp(), 1);
        $this->assertEquals($expectedRentalType, $actual->getRentalType());
        $this->assertEquals($rentalPlaceId, $actual->getRentalPlaceId());
        $this->assertEquals($tenantId, $actual->getTenantId());
        $this->assertEquals($dates, $actual->getDates());
    }

    public function getDifferentRentalPlaceTypes(): array
    {
        return [
            [
                RentalType::APARTMENT,
            ],
            [
                RentalType::HOTEL_ROOM
            ],
        ];
    }
}
