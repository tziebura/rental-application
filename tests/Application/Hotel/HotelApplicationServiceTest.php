<?php

namespace App\Tests\Application\Hotel;

use App\Application\Hotel\HotelApplicationService;
use App\Application\Hotel\HotelRoomBookingDTO;
use App\Application\Hotel\HotelRoomDTO;
use App\Domain\Address\Address;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelEventsPublisher;
use App\Domain\Hotel\HotelFactory;
use App\Domain\Hotel\HotelRepository;
use App\Domain\Space\SquareMeterException;
use App\Tests\Domain\Booking\BookingAssertion;
use App\Tests\Domain\Hotel\HotelAssertion;
use App\Tests\Domain\Hotel\HotelRoomAssertion;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelApplicationServiceTest extends TestCase
{
    use PrivatePropertyManipulator;

    private const TENANT_ID = '1';
    private const HOTEL_ID = '1';
    private const ROOM_NUMBER = 1;
    private const DESCRIPTION = 'description';
    private const ROOMS = [
        'living_room' => 20.0,
        'kitchen' => 10.0,
        'bedroom' => 25.5,
        'bathroom' => 15.2
    ];
    private const HOTEL_ROOM_ID = '2';
    private const NAME = 'name';
    private const STREET = 'street';
    private const BUILDING_NUMBER = '1';
    private const POSTAL_CODE = '12-123';
    private const CITY = 'city';
    private const COUNTRY = 'country';
    private array $days;

    private HotelRepository $hotelRepository;
    private HotelEventsPublisher $hotelRoomEventsPublisher;
    private BookingRepository $bookingRepository;

    private HotelApplicationService $subject;

    private Booking $actualBooking;

    public function setUp(): void
    {
        $this->days = [new DateTimeImmutable('01-01-2022'), new DateTimeImmutable('02-01-2022')];
        $this->hotelRepository = $this->createMock(HotelRepository::class);
        $this->hotelRoomEventsPublisher = $this->createMock(HotelEventsPublisher::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);

        $this->subject = new HotelApplicationService($this->hotelRepository, $this->hotelRoomEventsPublisher, $this->bookingRepository);
    }

    /**
     * @test
     */
    public function shouldAddHotelWithAllInformation()
    {
        $this->hotelRepository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Hotel $hotel) use (&$actual) {
                $actual = $hotel;
            }));

        $this->subject->add(
            self::NAME,
            self::STREET,
            self::BUILDING_NUMBER,
            self::POSTAL_CODE,
            self::CITY,
            self::COUNTRY
        );

        $expectedAddress = new Address(
            self::STREET,
            self::BUILDING_NUMBER,
            self::POSTAL_CODE,
            self::CITY,
            self::COUNTRY
        );

        HotelAssertion::assertThat($actual)
            ->hasNameEqualTo(self::NAME)
            ->hasAddressEqualTo($expectedAddress);
    }

    /**
     * @test
     */
    public function shouldAddHotelRoomWithAllInformation(): void
    {
        $this->givenHotelExists();
        $dto = $this->givenHotelRoomDto(self::ROOMS);

        $this->thenHotelShouldHaveHotelRoom();

        $this->subject->addHotelRoom($dto);
    }

    /**
     * @test
     */
    public function shouldNotAllowToCreateHotelRoomWithAtLeastOneSpaceThatHasSquareMeterEqualZero(): void
    {
        $roomsDefinition = self::ROOMS;
        $roomsDefinition['zeroRoom'] = 0;

        $this->givenHotelExists();
        $dto = $this->givenHotelRoomDto($roomsDefinition);

        $this->expectException(SquareMeterException::class);
        $this->thenHotelRoomShouldNeverBeSaved();

        $this->subject->addHotelRoom($dto);
    }

    /**
     * @test
     */
    public function shouldNotAllowToCreateHotelRoomWithAtLeastOneSpaceThatHasSquareMeterLessThanZero(): void
    {
        $roomsDefinition = self::ROOMS;
        $roomsDefinition['lessThanZeroRoom'] = -1;

        $this->givenHotelExists();
        $dto = $this->givenHotelRoomDto($roomsDefinition);

        $this->expectException(SquareMeterException::class);
        $this->thenHotelRoomShouldNeverBeSaved();

        $this->subject->addHotelRoom($dto);
    }
    /**
     * @test
     */
    public function shouldCreateBookingWhenHotelRoomBooked(): void
    {
        $this->givenHotelWithRoomExists();

        $this->thenBookingWillBeSaved();
        $this->subject->book(new HotelRoomBookingDTO(self::HOTEL_ID, self::ROOM_NUMBER, self::TENANT_ID,  $this->days));
    }

    public function shouldPublishHotelRoomBookedEvent(): void
    {
        $this->givenHotelWithRoomExists();

        $this->thenHotelRoomBookedEventShouldBePublished();
        $this->subject->book(new HotelRoomBookingDTO(self::HOTEL_ID, self::ROOM_NUMBER, self::TENANT_ID,  $this->days));
    }

    private function givenHotelExists(): void
    {
        $hotel = $this->givenHotel();

        $this->hotelRepository->expects($this->once())
            ->method('findById')
            ->with(self::HOTEL_ID)
            ->willReturn($hotel);
    }

    private function thenHotelShouldHaveHotelRoom(): void
    {
        $this->hotelRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Hotel $actual) {
                $actualHotelRoom = $this->getByReflection($actual, 'rooms')->first();
                HotelRoomAssertion::assertThat($actualHotelRoom)
                    ->hasHotelEqualTo($actual)
                    ->hasNumberEqualTo(self::ROOM_NUMBER)
                    ->hasDescriptionEqualTo(self::DESCRIPTION)
                    ->hasNumberOfRooms(count(self::ROOMS))
                    ->hasRooms(self::ROOMS);

                return true;
            }));
    }

    private function givenHotelWithRoomExists(): void
    {
        $hotel = $this->givenHotel();

        $hotel->addHotelRoom(
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            self::ROOMS
        );

        $hotelRoom = $this->getByReflection($hotel, 'rooms')->first();
        $this->setByReflection($hotelRoom, 'id', (int) self::HOTEL_ROOM_ID);

        $this->hotelRepository->expects($this->once())
            ->method('findById')
            ->with(self::HOTEL_ID)
            ->willReturn($hotel);
    }

    private function thenBookingWillBeSaved()
    {
        $this->bookingRepository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Booking $actual) {
                BookingAssertion::assertThat($actual)
                    ->isHotelRoomBooking()
                    ->hasTenantIdEqualTo(self::TENANT_ID)
                    ->hasDaysEqualTo($this->days);
            }));
    }

    private function thenHotelRoomBookedEventShouldBePublished(): void
    {
        $this->hotelRoomEventsPublisher->expects($this->once())
            ->method('publishHotelRoomBooked')
            ->with(
                self::HOTEL_ROOM_ID,
                self::HOTEL_ID,
                $this->days,
                self::TENANT_ID
            );
    }

    /**
     * @return Hotel
     */
    public function givenHotel(): Hotel
    {
        $hotel = (new HotelFactory())->create(
            self::NAME,
            self::STREET,
            self::BUILDING_NUMBER,
            self::POSTAL_CODE,
            self::CITY,
            self::COUNTRY
        );

        $this->setByReflection($hotel, 'id', (int) self::HOTEL_ID);
        return $hotel;
    }

    private function givenHotelRoomDto(array $roomsDefinition): HotelRoomDTO
    {
        return new HotelRoomDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            $roomsDefinition
        );
    }

    private function thenHotelRoomShouldNeverBeSaved(): void
    {
        $this->hotelRepository->expects($this->never())
            ->method('save');
    }
}
