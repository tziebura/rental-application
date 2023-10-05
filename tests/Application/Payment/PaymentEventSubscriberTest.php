<?php

namespace App\Tests\Application\Payment;

use App\Application\Payment\PaymentEventSubscriber;
use App\Domain\Agreement\AgreementAccepted;
use App\Domain\Booking\RentalType;
use App\Domain\Event\EventCreationTimeFactory;
use App\Domain\Event\EventIdFactory;
use App\Domain\EventChannel\EventChannel;
use App\Domain\Payment\PaymentCompleted;
use App\Domain\Payment\PaymentEventsPublisher;
use App\Domain\Payment\PaymentFactory;
use App\Domain\Payment\PaymentFailed;
use App\Domain\Payment\PaymentService;
use App\Domain\Payment\PaymentStatus;
use App\Tests\Domain\Payment\PaymentCompletedAssertion;
use App\Tests\Domain\Payment\PaymentFailedAssertion;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PaymentEventSubscriberTest extends TestCase
{
    private const OWNER_ID = 'OWNER_ID';
    private const TENANT_ID = 'TENANT_ID';
    private const PRICE = 100.0;
    private const RENTAL_PLACE_ID = 1;
    private const DAYS = ["2023-10-01", "2023-10-02", "2023-10-03"];
    private const RENTAL_TYPE = RentalType::APARTMENT;
    private const EVENT_ID = 'eventId';
    private const AMOUNT = 300.0;

    private PaymentEventSubscriber $subject;

    private EventChannel $eventChannel;
    private PaymentService $paymentService;

    public function setUp(): void
    {
        $this->eventChannel = $this->createMock(EventChannel::class);
        $this->paymentService = $this->createMock(PaymentService::class);

        $this->subject = new PaymentEventSubscriber(
            new PaymentEventsPublisher(
                new EventIdFactory(),
                new EventCreationTimeFactory(),
                $this->eventChannel
            ),
            $this->paymentService,
            new PaymentFactory()
        );
    }

    /**
     * @test
     */
    public function shouldPublishEventWhenSuccessfullyPaid(): void
    {
        $event = $this->givenAgreementAccepted();
        $this->givenSuccessfulPayment();

        $this->thenPaymentSuccessfulEventShouldBePublished();
        $this->subject->onAgreementAccepted($event);
    }

    /**
     * @test
     */
    public function shouldRecognizeThereIsNotEnoughMoney(): void
    {
        $event = $this->givenAgreementAccepted();
        $this->givenNotEnoughMoney();

        $this->thenPaymentFailedEventShouldBePublished();
        $this->subject->onAgreementAccepted($event);
    }

    private function givenAgreementAccepted(): AgreementAccepted
    {
        return new AgreementAccepted(
            self::EVENT_ID,
            new DateTimeImmutable(),
            self::RENTAL_TYPE,
            self::RENTAL_PLACE_ID,
            self::OWNER_ID,
            self::TENANT_ID,
            self::PRICE,
            self::DAYS
        );
    }

    private function thenPaymentSuccessfulEventShouldBePublished(): void
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with($this->callback(function (PaymentCompleted $actual) {
                PaymentCompletedAssertion::assertThat($actual)
                    ->hasSenderId(self::TENANT_ID)
                    ->hasRecipientId(self::OWNER_ID)
                    ->hasAmount(self::AMOUNT);

                return true;
            }));
    }

    private function givenSuccessfulPayment(): void
    {
        $this->paymentService->expects($this->once())
            ->method('transfer')
            ->with(
                self::TENANT_ID,
                self::OWNER_ID,
                self::AMOUNT
            )
            ->willReturn(PaymentStatus::SUCCESS);
    }

    private function givenNotEnoughMoney(): void
    {
        $this->paymentService->expects($this->once())
            ->method('transfer')
            ->with(
                self::TENANT_ID,
                self::OWNER_ID,
                self::AMOUNT
            )
            ->willReturn(PaymentStatus::NOT_ENOUGH_MONEY);
    }

    private function thenPaymentFailedEventShouldBePublished()
    {
        $this->eventChannel->expects($this->once())
            ->method('publish')
            ->with($this->callback(function (PaymentFailed $actual) {
                PaymentFailedAssertion::assertThat($actual)
                    ->hasSenderId(self::TENANT_ID)
                    ->hasRecipientId(self::OWNER_ID)
                    ->hasAmount(self::AMOUNT);

                return true;
            }));
    }
}
