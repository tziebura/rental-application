<?php

namespace App\Tests\Domain\Booking;

use App\Domain\Agreement\Agreement;
use App\Domain\Agreement\AgreementRepository;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\RentalType;
use App\Domain\Money\Money;
use App\Domain\Period\Period;
use App\Tests\Domain\Agreement\AgreementAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingDomainServiceTest extends TestCase
{
    private const RENTAL_TYPE     = 'hotel_room';
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID_1     = 'tenantId';
    private const TENANT_ID_2     = 'tenantId2';
    private const OWNER_ID        = 'OWNER_ID';
    private const PRICE           = 100.0;

    private BookingEventsPublisher $bookingEventsPublisher;
    private AgreementRepository $agreementRepository;
    private array $bookingDates = [];
    private array $bookingDatesWithCollision = [];
    private array $bookingDatesWithoutCollision = [];
    private DateTimeImmutable $bookingStart;
    private DateTimeImmutable $bookingEnd;

    private BookingDomainService $subject;

    public function setUp(): void
    {
        $this->bookingEventsPublisher = $this->createMock(BookingEventsPublisher::class);
        $this->agreementRepository = $this->createMock(AgreementRepository::class);

        $this->subject = new BookingDomainService(
            $this->bookingEventsPublisher,
            $this->agreementRepository
        );

        $this->bookingStart = (new DateTimeImmutable())->setTime(0, 0);
        $this->bookingEnd = $this->bookingStart->modify('+1days');

        $this->bookingDates = [
            $this->bookingStart->format('Y-m-d'),
            $this->bookingEnd->format('Y-m-d'),
        ];

        $this->bookingDatesWithCollision = [
            $this->bookingStart->format('Y-m-d'),
            $this->bookingStart->modify('+1days')->format('Y-m-d'),
            $this->bookingStart->modify('+2days')->format('Y-m-d'),
        ];

        $this->bookingDatesWithoutCollision = [
            $this->bookingStart->modify('+2days')->format('Y-m-d'),
            $this->bookingStart->modify('+3days')->format('Y-m-d'),
            $this->bookingStart->modify('+4days')->format('Y-m-d'),
        ];
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenNoOtherBookingsFound(): void
    {
        $booking = $this->givenApartmentBooking();

        $actual = $this->subject->accept($booking, []);

        BookingAssertion::assertThat($booking)
            ->isAccepted();

        AgreementAssertion::assertThat($actual)
            ->hasRentalTypeEqualTo(RentalType::APARTMENT)
            ->hasRentalPlaceIdEqualTo(self::RENTAL_PLACE_ID)
            ->hasOwnerIdEqualTo(self::OWNER_ID)
            ->hasTenantIdEqualTo(self::TENANT_ID_1)
            ->hasDaysEqualTo($this->bookingDates)
            ->hasPriceEqualTo(Money::of(self::PRICE));
    }

    /**
     * @test
     */
    public function shouldPublishEventWhenBookingIsAccepted(): void
    {
        $booking = $this->givenApartmentBooking();

        $this->thenBookingAcceptedEventShouldBePublished();
        $this->subject->accept($booking, []);
    }

    /**
     * @test
     */
    public function shouldRejectBookingWhenOtherWithCollisionFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [$this->givenAcceptedBookingWithCollision()];

        $actual = $this->subject->accept($booking, $bookings);

        BookingAssertion::assertThat($booking)
            ->isRejected();

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function shouldPublishEventWhenBookingIsRejected(): void
    {
        $booking = $this->givenBooking();
        $bookings = [$this->givenAcceptedBookingWithCollision()];

        $this->thenBookingRejectedEventShouldBePublished();
        $actual = $this->subject->accept($booking, $bookings);

        $this->assertNull($actual);
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenOtherWithoutCollisionFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [$this->givenAcceptedBookingWithoutCollision()];

        $this->subject->accept($booking, $bookings);

        BookingAssertion::assertThat($booking)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenOtherWithCollisionButNotAcceptedFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [$this->givenOpenBookingWithCollision()];

        $this->subject->accept($booking, $bookings);

        BookingAssertion::assertThat($booking)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenOthersWithoutCollisionFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [
            $this->givenOpenBookingWithCollision(),
            $this->givenAcceptedBookingWithoutCollision(),
            $this->givenRejectedBookingWithDaysCollision(),
        ];

        $this->subject->accept($booking, $bookings);

        BookingAssertion::assertThat($booking)
            ->isAccepted();
    }

    /**
     * @test
     */
    public function shouldRejectBookingWhenAtLeastOneWithCollisionFound(): void
    {
        $booking = $this->givenBooking();
        $bookings = [
            $this->givenOpenBookingWithCollision(),
            $this->givenAcceptedBookingWithoutCollision(),
            $this->givenRejectedBookingWithDaysCollision(),
            $this->givenAcceptedBookingWithCollision(),
        ];

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
            $this->bookingDates,
            self::OWNER_ID,
            Money::of(self::PRICE)
        );
    }

    private function givenAcceptedBookingWithCollision(): Booking
    {
        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            $this->bookingDatesWithCollision,
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $booking->accept($this->createMock(BookingEventsPublisher::class));
        return $booking;
    }

    private function thenBookingAcceptedEventShouldBePublished(): void
    {
        $this->bookingEventsPublisher->expects($this->once())
            ->method('publishBookingAccepted')
            ->with(RentalType::APARTMENT, self::RENTAL_PLACE_ID, self::TENANT_ID_1, $this->bookingDates);
    }

    private function thenBookingRejectedEventShouldBePublished(): void
    {
        $this->bookingEventsPublisher->expects($this->once())
            ->method('publishBookingRejected')
            ->with(self::RENTAL_TYPE, self::RENTAL_PLACE_ID, self::TENANT_ID_1, $this->bookingDates);
    }

    private function givenAcceptedBookingWithoutCollision(): Booking
    {
        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            $this->bookingDatesWithoutCollision,
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $booking->accept($this->createMock(BookingEventsPublisher::class));
        return $booking;
    }

    private function givenOpenBookingWithCollision(): Booking
    {
        return new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            $this->bookingDatesWithCollision,
            self::OWNER_ID,
            Money::of(self::PRICE)
        );
    }

    private function givenRejectedBookingWithDaysCollision(): Booking
    {
        $booking = new Booking(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_2,
            self::RENTAL_TYPE,
            $this->bookingDatesWithCollision,
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $booking->reject($this->createMock(BookingEventsPublisher::class));
        return $booking;
    }

    private function givenApartmentBooking(): Booking
    {
        return Booking::apartment(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID_1,
            Period::of($this->bookingStart, $this->bookingEnd),
            self::OWNER_ID,
            Money::of(self::PRICE)
        );
    }
}