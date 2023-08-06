<?php

namespace App\Tests\Application\HotelRoomOffer;

use App\Application\HotelRoomOffer\HotelRoomOfferDTO;
use App\Application\HotelRoomOffer\HotelRoomOfferService;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use App\Tests\Domain\HotelRoomOffer\HotelRoomOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomOfferServiceTest extends TestCase
{
    const HOTEL_ROOM_ID = '1';
    const PRICE = 10.0;
    private HotelRoomOfferRepository $hotelRoomOfferRepository;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private HotelRoomOfferService $subject;

    public function setUp(): void
    {
        $this->hotelRoomOfferRepository = $this->createMock(HotelRoomOfferRepository::class);

        $this->start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $this->end = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        $this->subject = new HotelRoomOfferService(
            $this->hotelRoomOfferRepository
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

    private function givenHotelRoomExists()
    {
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

    private function givenHotelRoomDto(): HotelRoomOfferDTO
    {
        return new HotelRoomOfferDTO(
            self::HOTEL_ROOM_ID,
            self::PRICE,
            $this->start,
            $this->end
        );
    }
}