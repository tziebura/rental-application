<?php

namespace App\Tests\Application\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use App\Application\Apartment\ApartmentBookingDTO;
use App\Application\Apartment\ApartmentDTO;
use App\Application\Apartment\OwnerNotFoundException;
use App\Domain\Apartment\ApartmentBookingException;
use App\Domain\Apartment\ApartmentDomainService;
use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Domain\Booking\RentalType;
use App\Domain\Money\Money;
use App\Domain\Period\PeriodException;
use App\Domain\RentalPlaceAvailability\RentalPlaceAvailability;
use App\Domain\Tenant\TenantNotFoundException;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentBuilder;
use App\Domain\Apartment\ApartmentEventsPublisher;
use App\Domain\Apartment\ApartmentFactory;
use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Owner\OwnerRepository;
use App\Domain\Period\Period;
use App\Domain\Space\SquareMeterException;
use App\Domain\Tenant\TenantRepository;
use App\Tests\Domain\Apartment\ApartmentAssertion;
use App\Tests\Domain\Booking\BookingAssertion;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentApplicationServiceTest extends TestCase
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
    private const OWNER_ID = '1';
    private const DESCRIPTION = 'description';
    private const TENANT_ID = '1';

    private ApartmentRepository $apartmentRepository;
    private ApartmentEventsPublisher $apartmentEventsPublisher;
    private BookingRepository $bookingRepository;
    private ApartmentApplicationService $subject;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;
    private OwnerRepository $ownerRepository;
    private TenantRepository $tenantRepository;
    private ApartmentOfferRepository $apartmentOfferRepository;
    private DateTimeImmutable $beforeStart;
    private DateTimeImmutable $afterStart;

    public function setUp(): void
    {
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);
        $this->ownerRepository = $this->createMock(OwnerRepository::class);
        $this->tenantRepository = $this->createMock(TenantRepository::class);
        $this->apartmentEventsPublisher = $this->createMock(ApartmentEventsPublisher::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->apartmentOfferRepository = $this->createMock(ApartmentOfferRepository::class);
        $this->start = (new DateTimeImmutable())->setTime(0, 0);
        $this->end = $this->start->modify('+1days');
        $this->beforeStart = $this->start->modify('-1days');
        $this->afterStart = $this->start->modify('+1days');

        $this->subject = new ApartmentApplicationService(
            $this->apartmentRepository,
            new ApartmentFactory($this->ownerRepository),
            $this->bookingRepository,
            new ApartmentDomainService(
                $this->apartmentRepository,
                $this->apartmentEventsPublisher,
                $this->tenantRepository,
                $this->bookingRepository,
                $this->apartmentOfferRepository
            )
        );
    }

    /**
     * @test
     */
    public function shouldCreateApartmentWithAllInformation(): void
    {
        $this->givenOwnerExists();
        $dto = $this->givenApartmentDto(self::ROOMS_DEFINITION);

        $this->thenApartmentShouldBeSaved();
        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeOwnerDoesNotExist(): void
    {
        $this->givenOwnerDoesNotExist();
        $dto = $this->givenApartmentDto(self::ROOMS_DEFINITION);

        $this->expectException(OwnerNotFoundException::class);
        $this->thenApartmentShouldNeverBeSaved();

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldNotAllowToCreateApartmentWithAtLeastOneSpaceThatHasSquareMeterEqualZero(): void
    {
        $roomsDefinition = self::ROOMS_DEFINITION;
        $roomsDefinition['zeroRoom'] = 0;

        $this->givenOwnerExists();
        $dto = $this->givenApartmentDto($roomsDefinition);

        $this->expectException(SquareMeterException::class);
        $this->thenApartmentShouldNeverBeSaved();

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldNotAllowToCreateApartmentWithAtLeastOneSpaceThatHasSquareMeterLessThanZero(): void
    {
        $roomsDefinition = self::ROOMS_DEFINITION;
        $roomsDefinition['lessThanZeroRoom'] = -1;

        $this->givenOwnerExists();
        $dto = $this->givenApartmentDto($roomsDefinition);

        $this->expectException(SquareMeterException::class);
        $this->thenApartmentShouldNeverBeSaved();

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldCreateBookingWhenApartmentBooked()
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();
        $this->givenApartmentOfferExists();

        $this->thenShouldSaveBooking();
        $this->subject->book($this->givenApartmentBookingDto());
    }

    /**
     * @test
     */
    public function shouldPublishApartmentBookedEvent(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();
        $this->givenApartmentOfferExists();

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

        $this->thenBookingShouldNeverBeSaved();
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

        $this->thenBookingShouldNeverBeSaved();
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

        $this->thenBookingShouldNeverBeSaved();
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
        $this->givenApartmentOfferExists();

        $dto = $this->givenApartmentBookingDto();

        $this->thenShouldSaveBooking();
        $this->thenShouldPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeWhenStartDateIsFromPastWhenBooking(): void
    {
        $this->givenApartmentExists();
        $this->givenTenantExists();
        $this->givenNoBookings();

        $dto = new ApartmentBookingDTO(
            self::APARTMENT_ID,
            self::TENANT_ID,
            new DateTimeImmutable('2023-09-30'),
            $this->end
        );

        $this->expectException(PeriodException::class);
        $this->expectExceptionMessage('Start date: 2023-09-30 is from the past.');

        $this->thenBookingShouldNeverBeSaved();
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

        $dto = new ApartmentBookingDTO(
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

        $this->thenBookingShouldNeverBeSaved();
        $this->thenShouldNeverPublishApartmentBookedEvent();

        $this->subject->book($dto);
    }

    private function givenApartmentExists()
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

    private function thenShouldSaveBooking()
    {
        $this->bookingRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Booking $actual) {
                BookingAssertion::assertThat($actual)
                    ->hasDaysEqualTo([$this->start->format('Y-m-d'), $this->end->format('Y-m-d')])
                    ->isApartmentBooking()
                    ->hasTenantIdEqualTo(self::TENANT_ID)
                    ->hasRentalPlaceIdEqualTo(self::APARTMENT_ID);

                return true;
            }));
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

    private function givenOwnerExists(): void
    {
        $this->ownerRepository->expects($this->once())
            ->method('exists')
            ->with(self::OWNER_ID)
            ->willReturn(true);
    }

    private function givenOwnerDoesNotExist(): void
    {
        $this->ownerRepository->expects($this->once())
            ->method('exists')
            ->with(self::OWNER_ID)
            ->willReturn(false);
    }

    private function givenApartmentDto(array $roomsDefinition): ApartmentDTO
    {
        return new ApartmentDTO(
            self::OWNER_ID,
            self::STREET,
            self::POSTAL_CODE,
            self::HOUSE_NUMBER,
            self::APARTMENT_NUMBER,
            self::CITY,
            self::COUNTRY,
            self::DESCRIPTION,
            $roomsDefinition
        );
    }

    private function thenApartmentShouldBeSaved(): void
    {
        $this->apartmentRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Apartment $actual) {
                ApartmentAssertion::assertThat($actual)
                    ->hasOwnerEqualTo(self::OWNER_ID)
                    ->hasDescriptionEqualTo(self::DESCRIPTION)
                    ->hasAddressEqualTo(
                        self::STREET,
                        self::POSTAL_CODE,
                        self::HOUSE_NUMBER,
                        self::APARTMENT_NUMBER,
                        self::CITY,
                        self::COUNTRY,
                    )
                    ->hasRoomsEqualTo(self::ROOMS_DEFINITION);

                return true;
            }));
    }

    private function thenApartmentShouldNeverBeSaved(): void
    {
        $this->apartmentRepository->expects($this->never())
            ->method('save');
    }

    private function givenApartmentBookingDto(): ApartmentBookingDTO
    {
        return new ApartmentBookingDTO(
            self::APARTMENT_ID,
            self::TENANT_ID,
            $this->start,
            $this->end
        );
    }

    private function givenApartmentDoesNotExist(): void
    {
        $this->apartmentRepository->expects($this->once())
            ->method('findById')
            ->with(self::APARTMENT_ID)
            ->willReturn(null);
    }

    private function thenBookingShouldNeverBeSaved(): void
    {
        $this->bookingRepository->expects($this->never())
            ->method('save');
    }

    private function thenShouldNeverPublishApartmentBookedEvent()
    {
        $this->apartmentEventsPublisher->expects($this->never())
            ->method('publishApartmentBooked');
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
            Money::of(100.0)
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
            Money::of(100.0)
        );

        $this->bookingRepository->expects($this->once())
            ->method('findAllAcceptedBy')
            ->with(RentalType::APARTMENT, self::APARTMENT_ID)
            ->willReturn([$booking]);
    }

    private function givenApartmentOfferExists(): void
    {
        $apartmentOffer = new ApartmentOffer(
            self::APARTMENT_ID,
            Money::of(100.0),
            RentalPlaceAvailability::of($this->start, $this->end)
        );

        $this->apartmentOfferRepository->expects($this->once())
            ->method('findForApartment')
            ->with(self::APARTMENT_ID)
            ->willReturn($apartmentOffer);
    }
}
