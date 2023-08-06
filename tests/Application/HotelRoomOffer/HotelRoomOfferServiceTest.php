<?php

namespace App\Tests\Application\HotelRoomOffer;

use App\Application\HotelRoomOffer\HotelRoomOfferDTO;
use App\Application\HotelRoomOffer\HotelRoomOfferService;
use App\Domain\HotelRoom\HotelRoomNotFoundException;
use App\Domain\HotelRoom\HotelRoomRepository;
use App\Domain\HotelRoomOffer\HotelRoomAvailabilityException;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use App\Domain\HotelRoomOffer\NotAllowedMoneyValueException;
use App\Tests\Domain\HotelRoomOffer\HotelRoomOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomOfferServiceTest extends TestCase
{
    private const HOTEL_ROOM_ID = '1';
    private const PRICE = 10.0;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private HotelRoomRepository $hotelRoomRepository;

    private HotelRoomOfferService $subject;

    public function setUp(): void
    {
        $this->hotelRoomOfferRepository = $this->createMock(HotelRoomOfferRepository::class);
        $this->hotelRoomRepository = $this->createMock(HotelRoomRepository::class);

        $this->start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $this->end = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        $this->subject = new HotelRoomOfferService(
            $this->hotelRoomOfferRepository,
            $this->hotelRoomRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateHotelRoomOffer(): void
    {
        $this->givenHotelRoomExists();
        $dto = $this->givenHotelRoomDto();

        $this->thenHotelRoomShouldBeSaved(
            $dto->getHotelRoomId(),
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
        $this->givenHotelRoomDoesNotExist();
        $dto = $this->givenHotelRoomDto();

        $this->expectException(HotelRoomNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Hotel room with ID %s does not exist', self::HOTEL_ROOM_ID));

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizePriceLessThanOrEqualToZero(): void
    {
        $this->givenHotelRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ROOM_ID,
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
        $this->givenHotelRoomExists();
        $dto = new HotelRoomOfferDTO(
            self::HOTEL_ROOM_ID,
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

    private function givenHotelRoomExists()
    {
        $this->hotelRoomRepository->expects($this->once())
            ->method('existsById')
            ->with(self::HOTEL_ROOM_ID)
            ->willReturn(true);
    }

    private function givenHotelRoomDto(): HotelRoomOfferDTO
    {
        return new HotelRoomOfferDTO(
            self::HOTEL_ROOM_ID,
            self::PRICE,
            $this->start,
            $this->end
        );
    }

    private function givenHotelRoomDoesNotExist()
    {
        $this->hotelRoomRepository->expects($this->once())
            ->method('existsById')
            ->with(self::HOTEL_ROOM_ID)
            ->willReturn(false);
    }

    private function thenHotelRoomShouldBeSaved(string $hotelRoomId, float $price, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $this->hotelRoomOfferRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (HotelRoomOffer $actual) use ($hotelRoomId, $price, $start, $end) {
                HotelRoomOfferAssertion::assertThat($actual)
                    ->hasHotelRoomId($hotelRoomId)
                    ->hasPrice($price)
                    ->hasAvailability($start, $end);

                return true;
            }));
    }
}