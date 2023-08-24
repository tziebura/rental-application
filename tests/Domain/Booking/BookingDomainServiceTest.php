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

    private BookingEventsPublisher $bookingEventsPublisher;
    private array $bookingDates;
    private array $bookingDatesWithCollision;

    private BookingDomainService $subject;

    public function setUp(): void
    {
        $this->bookingEventsPublisher = $this->createMock(BookingEventsPublisher::class);
        $this->subject = new BookingDomainService(
            $this->bookingEventsPublisher
        );

        $this->bookingDates = [
            new DateTimeImmutable('2023-08-24'),
            new DateTimeImmutable('2023-08-25'),
        ];

        $this->bookingDatesWithCollision = [
            new DateTimeImmutable('2023-08-24'),
            new DateTimeImmutable('2023-08-25'),
            new DateTimeImmutable('2023-08-26'),
        ];
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
    public function shouldPublisherEventWhenBookingIsAccepted(): void
    {
        $booking = $this->givenBooking();

        $this->thenBookingAcceptedEventShouldBePublished();
        $this->subject->accept($booking, []);
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
        return new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_1,
            self::RENTAL_TYPE,
            $this->bookingDates
        );
    }

    private function givenBookingWithCollision(): Booking
    {
        return new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            $this->bookingDatesWithCollision
        );
    }

    private function thenBookingAcceptedEventShouldBePublished(): void
    {
        $this->bookingEventsPublisher->expects($this->once())
            ->method('publishBookingAccepted')
            ->with(self::RENTAL_TYPE, self::RENTAL_PLACE_ID, self::TENANT_ID_1, $this->bookingDates);
    }
}