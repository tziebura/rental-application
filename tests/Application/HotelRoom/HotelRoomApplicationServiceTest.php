<?php

namespace App\Tests\Application\HotelRoom;

use App\Application\HotelRoom\HotelRoomApplicationService;
use App\Domain\Booking\Booking;
use App\Domain\Booking\BookingRepository;
use App\Domain\HotelRoom\HotelRoom;
use App\Domain\HotelRoom\HotelRoomEventsPublisher;
use App\Domain\HotelRoom\HotelRoomFactory;
use App\Domain\HotelRoom\HotelRoomRepository;
use App\Tests\Domain\Booking\BookingAssertion;
use App\Tests\Domain\HotelRoom\HotelRoomAssertion;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomApplicationServiceTest extends TestCase
{
    use PrivatePropertyManipulator;

    private const TENANT_ID = '1';
    private const HOTEL_ID = 'hotelId';
    private const ROOM_NUMBER = 1;
    private const DESCRIPTION = 'description';
    private const ROOMS = [
        'living_room' => 20.0,
        'kitchen' => 10.0,
        'bedroom' => 25.5,
        'bathroom' => 15.2
    ];
    private const HOTEL_ROOM_ID = '1';
    private array $days;

    private HotelRoomApplicationService $subject;
    private HotelRoomRepository $hotelRoomRepository;
    private HotelRoomEventsPublisher $hotelRoomEventsPublisher;
    private BookingRepository $bookingRepository;
    private Booking $actual;

    public function setUp(): void
    {
        $this->days = [new DateTimeImmutable('01-01-2022'), new DateTimeImmutable('02-01-2022')];
        $this->hotelRoomRepository = $this->createMock(HotelRoomRepository::class);
        $this->hotelRoomEventsPublisher = $this->createMock(HotelRoomEventsPublisher::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);

        $this->subject = new HotelRoomApplicationService(
            $this->hotelRoomRepository,
            $this->hotelRoomEventsPublisher,
            $this->bookingRepository
        );
    }

    /**
     * @test
     */
    public function shouldAddHotelRoomWithAllInformation(): void
    {
        $this->hotelRoomRepository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (HotelRoom $hotelRoom) use (&$actual) {
                $actual = $hotelRoom;
            }));

        $this->subject->add(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            self::ROOMS
        );

        HotelRoomAssertion::assertThat($actual)
            ->hasHotelIdEqualTo(self::HOTEL_ID)
            ->hasNumberEqualTo(self::ROOM_NUMBER)
            ->hasDescriptionEqualTo(self::DESCRIPTION)
            ->hasNumberOfRooms(count(self::ROOMS))
            ->hasRooms(self::ROOMS);
    }

    /**
     * @test
     */
    public function shouldCreateBookingWhenHotelRoomBooked(): void
    {
        $this->givenHotelRoom(self::HOTEL_ROOM_ID);

        $this->thenBookingWillBeSaved();
        $this->subject->book(self::HOTEL_ROOM_ID, $this->days, self::TENANT_ID);

        $this->thenBookingShouldBeCreated();
    }

    public function shouldPublishHotelRoomBookedEvent(): void
    {
        $this->givenHotelRoom(self::HOTEL_ROOM_ID);

        $this->thenHotelRoomBookedEventShouldBePublished();
        $this->subject->book(self::HOTEL_ROOM_ID, $this->days, self::TENANT_ID);
    }

    private function givenHotelRoom(string $hotelRoomId): void
    {
        $hotelRoom = (new HotelRoomFactory())->create(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            self::ROOMS
        );

        $this->setByReflection($hotelRoom, 'id', 1);

        $this->hotelRoomRepository->expects($this->once())
            ->method('findById')
            ->with($hotelRoomId)
            ->willReturn($hotelRoom);
    }

    private function thenBookingWillBeSaved()
    {
        $this->bookingRepository->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function (Booking $actual) {
                $this->actual = $actual;
            }));
    }

    private function thenBookingShouldBeCreated(): void
    {
        BookingAssertion::assertThat($this->actual)
            ->isHotelRoomBooking()
            ->hasTenantIdEqualTo(self::TENANT_ID)
            ->hasDaysEqualTo($this->days);
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
}
