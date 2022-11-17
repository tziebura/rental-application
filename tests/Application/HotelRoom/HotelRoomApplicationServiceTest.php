<?php

namespace App\Tests\Application\HotelRoom;

use App\Application\HotelRoom\HotelRoomApplicationService;
use App\Domain\Apartment\Booking;
use App\Domain\Apartment\BookingRepository;
use App\Domain\EventChannel\EventChannel;
use App\Domain\HotelRoom\HotelRoom;
use App\Domain\HotelRoom\HotelRoomFactory;
use App\Domain\HotelRoom\HotelRoomRepository;
use App\Tests\Domain\Apartment\BookingAssertion;
use App\Tests\Domain\HotelRoom\HotelRoomAssertion;
use App\Tests\PrivatePropertyManipulator;
use PHPUnit\Framework\TestCase;

class HotelRoomApplicationServiceTest extends TestCase
{
    use PrivatePropertyManipulator;

    private const TENANT_ID = '1';
    const HOTEL_ID = 'hotelId';
    const ROOM_NUMBER = 1;
    const DESCRIPTION = 'description';
    const ROOMS = [
        'living_room' => 20.0,
        'kitchen' => 10.0,
        'bedroom' => 25.5,
        'bathroom' => 15.2
    ];
    private array $days;

    private HotelRoomApplicationService $subject;
    private HotelRoomRepository $hotelRoomRepository;
    private EventChannel $eventChannel;
    private BookingRepository $bookingRepository;
    private Booking $actual;

    public function setUp(): void
    {
        $this->days = [new \DateTimeImmutable('01-01-2022'), new \DateTimeImmutable('02-01-2022')];
        $this->hotelRoomRepository = $this->createMock(HotelRoomRepository::class);
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);

        $this->subject = new HotelRoomApplicationService(
            $this->hotelRoomRepository,
            $this->eventChannel,
            $this->bookingRepository
        );
    }

    /**
     * @test
     */
    public function shouldAddHotelRoomWithAllInformation()
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
    public function shouldCreateBookingWhenHotelRoomBooked()
    {
        $hotelRoomId = '1';
        $this->givenHotelRoom($hotelRoomId);

        $this->thenBookingWillBeSaved();
        $this->subject->book($hotelRoomId, $this->days, self::TENANT_ID);

        $this->thenBookingShouldBeCreated();
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
}
