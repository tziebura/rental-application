<?php

namespace App\Tests\Application\ApartmentOffer;

use App\Application\ApartmentOffer\ApartmentOfferService;
use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Tests\Domain\ApartmentOffer\ApartmentOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentOfferServiceTest extends TestCase
{
    private const APARTMENT_ID = '1';
    private ApartmentOfferRepository $apartmentOfferRepository;

    private ApartmentOfferService $subject;

    public function setUp(): void
    {
        $this->apartmentOfferRepository = $this->createMock(ApartmentOfferRepository::class);
        $this->subject = new ApartmentOfferService(
            $this->apartmentOfferRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateApartmentOffer(): void
    {
        $this->givenApartmentExists();
        $price = 100.0;
        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $end = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        $this->thenApartmentOfferShouldBeAdded($price, $start, $end);
        $this->subject->add(self::APARTMENT_ID, $price, $start, $end);
    }

    private function givenApartmentExists(): void
    {
    }

    private function thenApartmentOfferShouldBeAdded(int $price, DateTimeImmutable $start, DateTimeImmutable $end): void
    {
        $this->apartmentOfferRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (ApartmentOffer $actual) use ($price, $start, $end) {
                ApartmentOfferAssertion::assertThat($actual)
                    ->hasApartmentIdEqualTo(self::APARTMENT_ID)
                    ->hasPriceEqualTo($price)
                    ->hasAvailabilityEqualTo($start, $end);

                return true;
            }));
    }
}