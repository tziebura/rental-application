<?php

namespace App\Tests\Application\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentFactory;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\Apartment\Booking;
use App\Domain\Apartment\BookingRepository;
use App\Domain\EventChannel\EventChannel;
use App\Tests\Domain\Apartment\ApartmentAssertion;
use App\Tests\Domain\Apartment\BookingAssertion;
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
    private EventChannel $eventChannel;
    private BookingRepository $bookingRepository;
    private ApartmentApplicationService $subject;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;
    private Booking $actualBooking;

    public function setUp(): void
    {
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->start = new DateTimeImmutable('01-01-2022');
        $this->end = new DateTimeImmutable('02-01-2022');

        $this->subject = new ApartmentApplicationService(
            $this->apartmentRepository,
            $this->eventChannel,
            $this->bookingRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateApartmentWithAllInformation()
    {
        $this->apartmentRepository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Apartment $apartment) use (&$actual) {
                $actual = $apartment;
            }));

        $this->subject->add(
            self::OWNER_ID,
            self::STREET,
            self::POSTAL_CODE,
            self::HOUSE_NUMBER,
            self::APARTMENT_NUMBER,
            self::CITY,
            self::COUNTRY,
            self::DESCRIPTION,
            self::ROOMS_DEFINITION
        );

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
    }

    /**
     * @test
     */
    public function shouldCreateBookingWhenApartmentBooked()
    {
        $this->givenApartment();

        $this->thenShouldSaveBooking();
        $this->subject->book(self::APARTMENT_ID, self::TENANT_ID, $this->start, $this->end);
        $this->thenBookingShouldBeCreated();
    }

    private function givenApartment()
    {
        $apartment = (new ApartmentFactory())->create(
            self::STREET,
            self::POSTAL_CODE,
            self::HOUSE_NUMBER,
            self::APARTMENT_NUMBER,
            self::CITY,
            self::COUNTRY,
            self::ROOMS_DEFINITION,
            self::OWNER_ID,
            self::DESCRIPTION
        );
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
}
