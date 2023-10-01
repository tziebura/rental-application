<?php

namespace App\Tests\Application\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use App\Application\Apartment\ApartmentBookingDTO;
use App\Application\Apartment\ApartmentDTO;
use App\Application\Apartment\OwnerNotFoundException;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentBuilder;
use App\Domain\Apartment\ApartmentEventsPublisher;
use App\Domain\Apartment\ApartmentFactory;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Owner\OwnerRepository;
use App\Domain\Period\Period;
use App\Domain\Space\SquareMeterException;
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
    private Booking $actualBooking;
    private OwnerRepository $ownerRepository;

    public function setUp(): void
    {
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);
        $this->ownerRepository = $this->createMock(OwnerRepository::class);
        $this->apartmentEventsPublisher = $this->createMock(ApartmentEventsPublisher::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->start = new DateTimeImmutable('01-01-2022');
        $this->end = new DateTimeImmutable('02-01-2022');

        $this->subject = new ApartmentApplicationService(
            $this->apartmentRepository,
            $this->apartmentEventsPublisher,
            new ApartmentFactory($this->ownerRepository),
            $this->bookingRepository
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
        $this->givenApartment();

        $this->thenShouldSaveBooking();
        $this->subject->book($this->givenApartmentBookingDto());
        $this->thenBookingShouldBeCreated();
    }

    /**
     * @test
     */
    public function shouldPublishApartmentBookedEvent(): void
    {
        $this->givenApartment();
        $this->thenShouldPublishApartmentBookedEvent();
        $this->subject->book($this->givenApartmentBookingDto());
    }

    private function givenApartment()
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
            ->will($this->returnCallback(function (Booking $booking) {
                $this->actualBooking = $booking;
            }));
    }

    private function thenBookingShouldBeCreated()
    {
        BookingAssertion::assertThat($this->actualBooking)
            ->hasDaysEqualTo([$this->start, $this->end])
            ->isApartmentBooking()
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasRentalPlaceIdEqualTo(self::APARTMENT_ID);
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
}
