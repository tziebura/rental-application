<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingDomainServiceTest extends TestCase
{
    private const RENTAL_TYPE = 'hotel_room';
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID = 'tenantId';

    private BookingDomainService $subject;

    public function setUp(): void
    {
        $this->subject = new BookingDomainService(
            $this->createMock(BookingEventsPublisher::class)
        );
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenNoOtherBookingsFound(): void
    {
        $dayOne = new DateTimeImmutable('2023-08-24');
        $dayTwo = new DateTimeImmutable('2023-08-25');
        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            self::RENTAL_TYPE,
            [$dayOne, $dayTwo]
        );

        $this->subject->accept($booking, []);

        BookingAssertion::assertThat($booking)
            ->isAccepted();
    }
}