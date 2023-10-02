<?php

namespace Apartment;

use App\Domain\Apartment\ApartmentBookingException;
use App\Domain\Apartment\ApartmentBuilder;
use App\Domain\Apartment\ApartmentDomainService;
use App\Domain\Apartment\ApartmentEventsPublisher;
use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\Apartment\NewApartmentBookingDto;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Booking\RentalType;
use App\Domain\Money\Money;
use App\Domain\Period\Period;
use App\Domain\Period\PeriodException;
use App\Domain\Tenant\TenantNotFoundException;
use App\Domain\Tenant\TenantRepository;
use App\Tests\Domain\Booking\BookingAssertion;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentDomainServiceTest extends TestCase
{
    use PrivatePropertyManipulator;

    private const APARTMENT_ID = 1;
    private const STREET = 'street';
    private const HOUSE_NUMBER = '1';
    private const POSTAL_CODE = '1-2';
    private const APARTMENT_NUMBER = '1';
    private const CITY = 'city';
    private const COUNTRY = 'country';
    private const ROOMS_DEFINITION = [
        'room1' => 10.0,
        'room2' => 20.5,
    ];
    private const DESCRIPTION = 'description';
    private const TENANT_ID = '1';
    private const OWNER_ID = '2';
    private const PRICE = 100.0;

    private ApartmentDomainService $subject;
    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;
    private TenantRepository $tenantRepository;
    private BookingRepository $bookingRepository;

    private DateTimeImmutable $start;
    private DateTimeImmutable $end;
    private DateTimeImmutable$beforeStart;
    private DateTimeImmutable $afterStart;

    public function setUp(): void
    {
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);
        $this->apartmentEventsPublisher = $this->createMock(ApartmentEventsPublisher::class);
        $this->tenantRepository = $this->createMock(TenantRepository::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);

        $this->start = new DateTimeImmutable();
        $this->end = $this->start->modify('+1days');
        $this->beforeStart = $this->start->modify('-1days');
        $this->afterStart = $this->start->modify('+1days');

        $this->subject = new ApartmentDomainService(
            $this->apartmentRepository,
            $this->apartmentEventsPublisher,
            $this->tenantRepository,
            $this->bookingRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateBookingWhenApartmentBooked()
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();

        $actual = $this->subject->book($this->givenApartmentBookingDto());
        BookingAssertion::assertThat($actual)
            ->isApartmentBooking()
            ->hasRentalPlaceIdEqualTo(self::APARTMENT_ID)
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo([
                $this->start,
                $this->end
            ])
            ->hasPriceEqualTo(Money::of(self::PRICE))
            ->hasOwnerIdEqualTo(self::OWNER_ID);
    }

    /**
     * @test
     */
    public function shouldPublishApartmentBookedEvent(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();

        $this->thenShouldPublishApartmentBookedEvent();
        $this->subject->book($this->givenApartmentBookingDto());
    }

    /**
     * @test
     */
    public function shouldRecognizeApartmentDoesNotExistWhenBooking(): void
    {
        $this->givenApartmentDoesNotExist();
        $dto = $this->givenApartmentBookingDto();

        $this->expectException(ApartmentNotFoundException::class);

        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeTenantDoesNotExistWhenBooking(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantDoesNotExist();

        $dto = $this->givenApartmentBookingDto();

        $this->expectException(TenantNotFoundException::class);

        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeWhenHaveBookingsWithGivenPeriod(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenAcceptedBookingInGivenPeriod();

        $dto = $this->givenApartmentBookingDto();

        $this->expectException(ApartmentBookingException::class);
        $this->expectExceptionMessage('There are accepted booking in given period.');

        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    /**
     * @test
     */
    public function shouldAllowToBookApartmentWhenFoundAcceptedBookingsInDifferentPeriod(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenAcceptedBookingInDifferentPeriod();

        $dto = $this->givenApartmentBookingDto();

        $this->thenShouldPublishApartmentBookedEvent();

        $actual = $this->subject->book($dto);
        BookingAssertion::assertThat($actual)
            ->isApartmentBooking()
            ->hasRentalPlaceIdEqualTo(self::APARTMENT_ID)
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo([
                $this->start,
                $this->end
            ]);
    }

    /**
     * @test
     */
    public function shouldRecognizeWhenStartDateIsFromPastWhenBooking(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();

        $dto = new NewApartmentBookingDTO(
            self::APARTMENT_ID,
            self::TENANT_ID,
            new DateTimeImmutable('2023-09-30'),
            $this->end
        );

        $this->expectException(PeriodException::class);
        $this->expectExceptionMessage('Start date: 2023-09-30 is from the past.');

        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeWhenEndDateIsBeforeStartDateWhenBooking(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();

        $dto = new NewApartmentBookingDTO(
            self::APARTMENT_ID,
            self::TENANT_ID,
            $this->end,
            $this->start
        );

        $this->expectException(PeriodException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date: %s of period is after end date: %s.',
            $this->end->format('Y-m-d'),
            $this->start->format('Y-m-d')
        ));

        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    private function givenApartmentExists(): void
    {
        $apartment = ApartmentBuilder::create()
            ->withStreet(self::STREET)
            ->withPostalCode(self::POSTAL_CODE)
            ->withHouseNumber(self::HOUSE_NUMBER)
            ->withApartmentNumber(self::APARTMENT_NUMBER)
            ->withCity(self::CITY)
            ->withCountry(self::COUNTRY)
            ->withRoomsDefinition(self::ROOMS_DEFINITION)
            ->withOwnerId(self::OWNER_ID)
            ->withDescription(self::DESCRIPTION)
            ->build();
        $this->setByReflection($apartment, 'id', self::APARTMENT_ID);

        $this->apartmentRepository->expects($this->once())
            ->method('findById')
            ->with(self::APARTMENT_ID)
            ->willReturn($apartment);
    }

    private function givenTenantExists(): void
    {
        $this->tenantRepository->expects($this->once())
            ->method('exists')
            ->with(self::TENANT_ID)
            ->willReturn(true);
    }

    private function givenTenantDoesNotExist(): void
    {
        $this->tenantRepository->expects($this->once())
            ->method('exists')
            ->with(self::TENANT_ID)
            ->willReturn(false);
    }

    private function givenNoBookings(): void
    {
        $this->bookingRepository->expects($this->once())
            ->method('findAllAcceptedBy')
            ->with(RentalType::APARTMENT, self::APARTMENT_ID)
            ->willReturn([]);
    }

    private function givenAcceptedBookingInGivenPeriod(): void
    {
        $booking = Booking::apartment(
            self::APARTMENT_ID,
            self::TENANT_ID,
            new Period($this->beforeStart, $this->afterStart),
            self::OWNER_ID,
            Money::of(10.0)
        );

        $this->bookingRepository->expects($this->once())
            ->method('findAllAcceptedBy')
            ->with(RentalType::APARTMENT, self::APARTMENT_ID)
            ->willReturn([$booking]);
    }

    private function givenAcceptedBookingInDifferentPeriod()
    {
        $booking = Booking::apartment(
            self::APARTMENT_ID,
            self::TENANT_ID,
            new Period($this->beforeStart->modify('-10days'), $this->beforeStart),
            self::OWNER_ID,
            Money::of(10.0)
        );

        $this->bookingRepository->expects($this->once())
            ->method('findAllAcceptedBy')
            ->with(RentalType::APARTMENT, self::APARTMENT_ID)
            ->willReturn([$booking]);
    }

    private function givenApartmentBookingDto(): NewApartmentBookingDTO
    {
        return new NewApartmentBookingDTO(
            self::APARTMENT_ID,
            self::TENANT_ID,
            $this->start,
            $this->end
        );
    }

    private function thenShouldPublishApartmentBookedEvent(): void
    {
        $this->apartmentEventsPublisher->expects($this->once())
            ->method('publishApartmentBooked')
            ->with(
                self::APARTMENT_ID,
                self::OWNER_ID,
                self::TENANT_ID,
                new Period($this->start, $this->end)
            );
    }

    private function givenApartmentDoesNotExist(): void
    {
        $this->apartmentRepository->expects($this->once())
            ->method('findById')
            ->with(self::APARTMENT_ID)
            ->willReturn(null);
    }

    private function thenShouldNeverPublishApartmentBookedEvent(): void
    {
        $this->apartmentEventsPublisher->expects($this->never())
            ->method('publishApartmentBooked');
    }
}