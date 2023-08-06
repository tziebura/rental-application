<?php

namespace App\Tests\Application\HotelRoomOffer;

use App\Application\HotelRoomOffer\HotelRoomOfferService;
use App\Domain\HotelRoomOffer\HotelRoomOffer;
use App\Domain\HotelRoomOffer\HotelRoomOfferRepository;
use App\Tests\Domain\HotelRoomOffer\HotelRoomOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HotelRoomOfferServiceTest extends TestCase
{
    private HotelRoomOfferRepository $hotelRoomOfferRepository;

    private HotelRoomOfferService $subject;

    public function setUp(): void
    {
        $this->hotelRoomOfferRepository = $this->createMock(HotelRoomOfferRepository::class);

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

        $hotelRoomId = '1';
        $price = 10.0;
        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $end = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        $this->thenHotelRoomShouldBeSaved($hotelRoomId, $price, $start, $end);
        $this->subject->add($hotelRoomId, $price, $start, $end);
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
}