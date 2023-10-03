<?php

namespace App\Tests\Application\Booking;

use App\Application\Booking\AcceptBooking;
use App\Application\Booking\BookingCommandHandler;
use App\Application\Booking\RejectBooking;
use App\Domain\Agreement\Agreement;
use App\Domain\Agreement\AgreementRepository;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingDomainService;
use App\Domain\Booking\BookingEventsPublisher;
use App\Domain\Booking\BookingRepository;
use App\Domain\Booking\RentalType;
use App\Domain\Money\Money;
use App\Domain\Period\Period;
use App\Tests\Domain\Agreement\AgreementAssertion;
use App\Tests\Domain\Booking\BookingAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingCommandHandlerTest extends TestCase
{
    private const BOOKING_ID = '1';
    private const RENTAL_TYPE = 'hotel_room';
    private const RENTAL_PLACE_ID = 1;
    private const TENANT_ID = 'tenantId';
    private const OWNER_ID = 'ownerId';
    private const PRICE = 100.0;

    private BookingRepository $repository;
    private BookingEventsPublisher $bookingEventsPublisher;
    private BookingCommandHandler $subject;
    private Booking $actual;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function setUp(): void
    {
        $this->repository = $this->createMock(BookingRepository::class);
        $this->bookingEventsPublisher = $this->createMock(BookingEventsPublisher::class);
        $this->agreementRepository = $this->createMock(AgreementRepository::class);

        $this->start = (new DateTimeImmutable())->setTime(0, 0);
        $this->end = $this->start->modify('+1days');

        $this->subject = new BookingCommandHandler(
            $this->repository,
            $this->bookingEventsPublisher,
            new BookingDomainService($this->bookingEventsPublisher),
            $this->agreementRepository
        );
    }

    /**
     * @test
     */
    public function shouldSubscribeToEvents(): void
    {
        $expectedEvents = [
            RejectBooking::class => ['reject'],
            AcceptBooking::class => ['accept'],
        ];

        $this->assertEquals($expectedEvents, BookingCommandHandler::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldRejectBooking(): void
    {
        $this->givenBooking();
        $command = $this->givenRejectBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->reject($command);
        $this->thenBookingShouldBeRejected();
    }

    private function givenRejectBookingCommand(): RejectBooking
    {
        return new RejectBooking(self::BOOKING_ID);
    }

    private function thenBookingShouldBeRejected(): void
    {
        BookingAssertion::assertThat($this->actual)
            ->isRejected();
    }

    /**
     * @test
     */
    public function shouldAcceptBookingWhenBookingWithCollisionNotFound(): void
    {
        $this->givenBookingsWithoutCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->accept($command);
        $this->thenBookingShouldBeAccepted();
    }

    /**
     * @test
     */
    public function shouldRejectBookingWhenBookingWithCollisionFound(): void
    {
        $this->givenBookingsWithCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenShouldSaveBooking();
        $this->subject->accept($command);
        $this->thenBookingShouldBeRejected();
    }

    /**
     * @test
     */
    public function shouldCreateAgreementWhenBookingAccepted(): void
    {
        $this->givenBookingsWithoutCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenAgreementShouldBeSaved();
        $this->subject->accept($command);
    }

    /**
     * @test
     */
    public function shouldNotCreateAgreementWhenBookingIsRejectedDuringAcceptance(): void
    {
        $this->givenBookingsWithCollision();
        $this->givenBooking();
        $command = $this->givenAcceptBookingCommand();

        $this->thenAgreementShouldNeverBeSaved();
        $this->subject->accept($command);
    }

    private function givenAcceptBookingCommand(): AcceptBooking
    {
        return new AcceptBooking(self::BOOKING_ID);
    }

    private function thenBookingShouldBeAccepted(): void
    {
        BookingAssertion::assertThat($this->actual)
            ->isAccepted();
    }

    private function givenBooking(): void
    {
        $booking = Booking::apartment(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            Period::of($this->start, $this->end),
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $this->repository->expects($this->once())
            ->method('findById')
            ->with(self::BOOKING_ID)
            ->willReturn($booking);
    }

    private function thenShouldSaveBooking(): void
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Booking $booking) {
                $this->actual = $booking;
            }));
    }

    private function givenBookingsWithoutCollision(): void
    {
        $periodStart = (new DateTimeImmutable())->setTime(0, 0);
        $periodEnd = $periodStart->modify('+2days');

        $booking = Booking::apartment(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            Period::of($periodStart, $periodEnd),
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $this->repository->expects($this->once())
            ->method('findAllBy')
            ->with(RentalType::APARTMENT, self::RENTAL_PLACE_ID)
            ->willReturn([$booking]);
    }

    private function givenBookingsWithCollision(): void
    {
        $periodStart = (new DateTimeImmutable())->setTime(0, 0);
        $periodEnd = $periodStart->modify('+2days');

        $booking = Booking::apartment(
            self::RENTAL_PLACE_ID,
            self::TENANT_ID,
            Period::of($periodStart, $periodEnd),
            self::OWNER_ID,
            Money::of(self::PRICE)
        );

        $booking->accept($this->bookingEventsPublisher);

        $this->repository->expects($this->once())
            ->method('findAllBy')
            ->with(RentalType::APARTMENT, self::RENTAL_PLACE_ID)
            ->willReturn([$booking]);
    }

    private function thenAgreementShouldBeSaved(): void
    {
        $this->agreementRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Agreement $actual) {
                AgreementAssertion::assertThat($actual)
                    ->hasRentalTypeEqualTo(RentalType::APARTMENT)
                    ->hasRentalPlaceIdEqualTo(self::RENTAL_PLACE_ID)
                    ->hasOwnerIdEqualTo(self::OWNER_ID)
                    ->hasTenantIdEqualTo(self::TENANT_ID)
                    ->hasDaysEqualTo([
                        $this->start->format('Y-m-d'),
                        $this->end->format('Y-m-d')
                    ])
                    ->hasPriceEqualTo(Money::of(self::PRICE));
                return true;
            }));
    }

    private function thenAgreementShouldNeverBeSaved(): void
    {
        $this->agreementRepository->expects($this->never())
            ->method('save');
    }
}
