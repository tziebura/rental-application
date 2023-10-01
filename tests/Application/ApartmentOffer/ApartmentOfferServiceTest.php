<?php

namespace App\Tests\Application\ApartmentOffer;

use App\Application\ApartmentOffer\ApartmentOfferDTO;
use App\Application\ApartmentOffer\ApartmentOfferService;
use App\Domain\Apartment\ApartmentNotFoundException;
use App\Domain\Apartment\ApartmentRepository;
use App\Domain\ApartmentOffer\ApartmentOffer;
use App\Domain\ApartmentOffer\ApartmentOfferRepository;
use App\Domain\Money\NotAllowedMoneyValueException;
use App\Domain\RentalPlaceAvailability\RentalPlaceAvailabilityException;
use App\Tests\Domain\ApartmentOffer\ApartmentOfferAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ApartmentOfferServiceTest extends TestCase
{
    private const APARTMENT_ID = '1';
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private ApartmentOfferRepository $apartmentOfferRepository;
    private ApartmentRepository $apartmentRepository;

    private ApartmentOfferService $subject;


    public function setUp(): void
    {
        $this->apartmentOfferRepository = $this->createMock(ApartmentOfferRepository::class);
        $this->apartmentRepository = $this->createMock(ApartmentRepository::class);

        $now = (new DateTimeImmutable())->setTime(0, 0);
        $this->start = $now;
        $this->end = $now->modify('+3days');

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

    /**
     * @test
     */
    public function shouldRecognizePriceIsNotGreaterThanZero(): void
    {
        $this->givenApartmentExists();

        $dto = new ApartmentOfferDTO(
            self::APARTMENT_ID,
            0,
            $this->start,
            $this->end
        );

        $this->expectException(NotAllowedMoneyValueException::class);
        $this->expectExceptionMessage('Price 0 is not greater than zero.');

        $this->subject->add($dto);
    }

    /**
     * @test
     */
    public function shouldRecognizeStartIsAfterEnd(): void
    {
        $this->givenApartmentExists();

        $dto = new ApartmentOfferDTO(
            self::APARTMENT_ID,
            100.0,
            $this->end,
            $this->start
        );

        $this->expectException(RentalPlaceAvailabilityException::class);
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

        $this->givenApartmentExists();

        $dto = new ApartmentOfferDTO(
            self::APARTMENT_ID,
            100.0,
            $start,
            $end
        );

        $this->expectException(RentalPlaceAvailabilityException::class);
        $this->expectExceptionMessage(sprintf(
            'Start date must be at least today, %s given.',
            $start->format('Y-m-d'),
        ));

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

    private function givenApartmentDto(): ApartmentOfferDTO
    {
        return new ApartmentOfferDTO(
            self::APARTMENT_ID,
            100.0,
            $this->start,
            $this->end
        );
    }
}