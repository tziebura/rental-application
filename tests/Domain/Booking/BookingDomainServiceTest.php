<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingDomainServiceTest extends TestCase
{
    private const RENTAL_TYPE     = 'hotel_room';
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID_1     = 'tenantId';
    private const TENANT_ID_2     = 'tenantId2';

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
        $booking = $this->givenBooking();

        $this->subject->accept($booking, []);

        BookingAssertion::assertThat($booking)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldRejectBookingWhenOtherWithCollisionFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [$this->givenBookingWithCollision()];

        $this->subject->accept($booking, $bookings);

        BookingAssertion::assertThat($booking)
            ->isRejected();
    }

    public function givenBooking(): Booking
    {
        $dayOne = new DateTimeImmutable('2023-08-24');
        $dayTwo = new DateTimeImmutable('2023-08-25');

        return new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_1,
            self::RENTAL_TYPE,
            [$dayOne, $dayTwo]
        );
    }

    private function givenBookingWithCollision(): Booking
    {
        $dayOne   = new DateTimeImmutable('2023-08-24');
        $dayTwo   = new DateTimeImmutable('2023-08-25');
        $dayThree = new DateTimeImmutable('2023-08-26');

        return new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            [$dayOne, $dayTwo, $dayThree]
        );
    }
}