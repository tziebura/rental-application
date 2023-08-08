<?php

namespace App\Tests\Application\HotelRoomOffer;

use App\Application\HotelRoomOffer\HotelRoomOfferDTO;
use App\Application\HotelRoomOffer\HotelRoomOfferService;
use App\Domain\Hotel\Hotel;
use App\Domain\Hotel\HotelFactory;
use App\Domain\Hotel\HotelRepository;
use App\Domain\Hotel\HotelRoomNotFoundException;
use App\Domain\Hotel\HotelRoomRepository;
use App\Domain\HotelRoomOffer\HotelRoomAvailabilityException;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use App\Domain\HotelRoomOffer\NotAllowedMoneyValueException;
use App\Tests\Domain\HotelRoomOffer\HotelRoomOfferAssertion;
use App\Tests\PrivatePropertyManipulator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomOfferServiceTest extends TestCase
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
    private const PRICE = 10.0;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private HotelRepository $hotelRepository;

    private HotelRoomOfferService $subject;

    public function setUp(): void
    {
        $this->hotelRoomOfferRepository = $this->createMock(HotelRoomOfferRepository::class);
        $this->hotelRepository = $this->createMock(HotelRepository::class);

        $this->start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $this->end = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        $this->subject = new HotelRoomOfferService(
            $this->hotelRoomOfferRepository,
            $this->hotelRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateHotelRoomOffer(): void
    {
        $this->givenHotelWithRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::PRICE,
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $this->thenHotelRoomShouldBeSaved(
            $dto->getHotelRoomNumber(),
            $dto->getPrice(),
            $dto->getStart(),
            $dto->getEnd()
        );

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeHotelRoomDoesNotExist(): void
    {
        $this->givenHotelWithoutRoom();
        $dto = $this->givenHotelRoomDto();

        $this->expectException(HotelRoomNotFoundException::class);
        $this->expectExceptionMessage('Hotel room with number 1 does not exist');

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizePriceLessThanOrEqualToZero(): void
    {
        $this->givenHotelWithRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            0,
            $this->start,
            $this->end
        );

        $this->expectException(NotAllowedMoneyValueException::class);
        $this->expectExceptionMessage('Price 0 is lower than or equal to zero.');

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeStartIsAfterEnd(): void
    {
        $this->givenHotelWithRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::PRICE,
            $this->end,
            $this->start
        );

        $this->expectException(HotelRoomAvailabilityException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date %s of availability is after end date %s.',
            $this->end->format('Y-m-d'),
            $this->start->format('Y-m-d'),
        ));

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeStartDateEarlierThanToday(): void
    {
        $start = (new DateTimeImmutable())->modify('-1days');
        $end = (new DateTimeImmutable())->modify('+14days');

        $this->givenHotelWithRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::PRICE,
            $start,
            $end
        );

        $this->expectException(HotelRoomAvailabilityException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date must be at least today, %s given.',
            $start->format('Y-m-d'),
        ));

        $this->subject->add($dto);
    }

    private function givenHotelWithRoomExists()
    {
        $hotel = $this->givenHotelWithRoom();

        $this->hotelRepository->expects($this->once())
            ->method('findById')
            ->with(self::HOTEL_ID)
            ->willReturn($hotel);
    }

    private function givenHotelRoomDto(): HotelRoomOfferDTO
    {
        return new HotelRoomOfferDTO(
            self::HOTEL_ID,
            self::ROOM_NUMBER,
            self::PRICE,
            $this->start,
            $this->end
        );
    }

    private function givenHotelWithoutRoom(): void
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
        $this->hotelRepository->expects($this->once())
            ->method('findById')
            ->with(self::HOTEL_ID)
            ->willReturn($hotel);
    }

    private function thenHotelRoomShouldBeSaved(string $hotelRoomNumber, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->hotelRoomOfferRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (HotelRoomOffer $actual) use ($hotelRoomNumber, $price, $start, $end) {
                HotelRoomOfferAssertion::assertThat($actual)
                    ->hasHotelRoomNumber($hotelRoomNumber)
                    ->hasPrice($price)
                    ->hasAvailability($start, $end);

                return true;
            }));
    }

    private function givenHotelWithRoom(): Hotel
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

        $hotel->addHotelRoom(
            self::ROOM_NUMBER,
            self::DESCRIPTION,
            self::ROOMS
        );

        $hotelRoom = $this->getByReflection($hotel, 'rooms')->first();
        $this->setByReflection($hotelRoom, 'id', (int) self::HOTEL_ROOM_ID);
        return $hotel;
    }
}