<?php

namespace Application\Agreement;

use App\Tests\Domain\Agreement\AgreementAcceptedAssertion;
use App\Application\Agreement\AgreementApplicationService;
use App\Domain\Agreement\AgreementAccepted;
use App\Domain\Agreement\AgreementBuilder;
use App\Domain\Agreement\AgreementEventsPublisher;
use App\Domain\Agreement\AgreementRepository;
use App\Domain\Booking\RentalType;
use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use App\Domain\Money\Money;
use PHPUnit\Framework\TestCase;

class AgreementApplicationServiceTest extends TestCase
{
    private const AGREEMENT_ID = 1;
    private const OWNER_ID = 'OWNER_ID';
    private const TENANT_ID = 'TENANT_ID';
    private const PRICE = 100.0;
    private const RENTAL_PLACE_ID = 1;
    private const DAYS = ["2023-10-01", "2023-10-02", "2023-10-03"];
    const RENTAL_TYPE = RentalType::APARTMENT;

    private AgreementRepository $agreementRepository;
    private EventChannel $eventChannel;

    private AgreementApplicationService $subject;

    public function setUp(): void
    {
        $this->agreementRepository = $this->createMock(AgreementRepository::class);
        $this->eventChannel = $this->createMock(EventChannel::class);

        $this->subject = new AgreementApplicationService(
            $this->agreementRepository,
            new AgreementEventsPublisher(
                new EventIdFactory(),
                new EventCreationTimeFactory(),
                $this->eventChannel
            )
        );
    }

    /**
     * @test
     */
    public function shouldAcceptAgreement(): void
    {
        $this->givenExistingAgreement();

        $this->thenShouldPublishAgreementAcceptedEvent();
        $this->subject->accept(self::AGREEMENT_ID);
    }

    private function givenExistingAgreement()
    {
        $agreement = AgreementBuilder::create()
            ->withOwnerId(self::OWNER_ID)
            ->withTenantId(self::TENANT_ID)
            ->withPrice(Money::of(self::PRICE))
            ->withRentalType(self::RENTAL_TYPE)
            ->withRentalPlaceId(self::RENTAL_PLACE_ID)
            ->withDays(self::DAYS)
            ->build();

        $this->agreementRepository->expects($this->once())
            ->method('findById')
            ->with(self::AGREEMENT_ID)
            ->willReturn($agreement);
    }

    private function thenShouldPublishAgreementAcceptedEvent(): void
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with($this->callback(function (AgreementAccepted $actual) {
                AgreementAcceptedAssertion::assertThat($actual)
                    ->hasRentalType(self::RENTAL_TYPE)
                    ->hasRentalPlaceId(self::RENTAL_PLACE_ID)
                    ->hasOwnerId(self::OWNER_ID)
                    ->hasTenantId(self::TENANT_ID)
                    ->hasPrice(self::PRICE)
                    ->hasDays(self::DAYS);

                return true;
            }));
    }
}
