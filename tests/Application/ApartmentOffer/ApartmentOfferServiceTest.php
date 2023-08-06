<?php

namespace App\Tests\Application\ApartmentOffer;

use App\Application\ApartmentOffer\ApartmentOfferDto;
use App\Application\ApartmentOffer\ApartmentOfferService;
use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Tests\Domain\ApartmentOffer\ApartmentOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentOfferServiceTest extends TestCase
{
    private const APARTMENT_ID = '1';
    private ApartmentOfferRepository $apartmentOfferRepository;
    private ApartmentRepository $apartmentRepository;

    private ApartmentOfferService $subject;

    public function setUp(): void
    {
        $this->apartmentOfferRepository = $this->createMock(ApartmentOfferRepository::class);
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);

        $this->subject = new ApartmentOfferService(
            $this->apartmentOfferRepository,
            $this->apartmentRepository
        );
    }

    /**
     * @test
     */
    public function shouldCreateApartmentOfferWhenApartmentExists(): void
    {
        $this->givenApartmentExists();
        $dto = $this->givenApartmentDto();

        $this->thenApartmentOfferShouldBeAdded($dto->getPrice(), $dto->getStart(), $dto->getEnd());
        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeApartmentDoesNotExist(): void
    {
        $this->givenApartmentDoesNotExist();
        $dto = $this->givenApartmentDto();

        $this->expectException(ApartmentNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Apartment with ID %s does not exist', self::APARTMENT_ID));

        $this->subject->add($dto);
    }

    private function givenApartmentDoesNotExist(): void
    {
        $this->apartmentRepository->expects($this->once())
            ->method('existsById')
            ->with(self::APARTMENT_ID)
            ->willReturn(false);
    }

    private function givenApartmentExists(): void
    {
        $this->apartmentRepository->expects($this->once())
            ->method('existsById')
            ->with(self::APARTMENT_ID)
            ->willReturn(true);
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

    private function givenApartmentDto(): ApartmentOfferDto
    {
        $price = 100.0;
        $start = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-06');
        $end   = DateTimeImmutable::createFromFormat('Y-m-d', '2023-08-20');

        return new ApartmentOfferDto(
            self::APARTMENT_ID,
            $price,
            $start,
            $end
        );
    }
}