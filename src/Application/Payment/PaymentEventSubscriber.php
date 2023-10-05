<?php

namespace App\Application\Payment;

use App\Domain\Agreement\AgreementAccepted;
use App\Domain\Payment\PaymentEventsPublisher;
use App\Domain\Payment\PaymentFactory;
use App\Domain\Payment\PaymentService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentEventSubscriber implements EventSubscriberInterface
{
    private PaymentEventsPublisher $publisher;
    private PaymentService $paymentService;
    private PaymentFactory $paymentFactory;

    public function __construct(PaymentEventsPublisher $publisher, PaymentService $paymentService, PaymentFactory $paymentFactory)
    {
        $this->publisher = $publisher;
        $this->paymentService = $paymentService;
        $this->paymentFactory = $paymentFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AgreementAccepted::class => 'onAgreementAccepted',
        ];
    }

    public function onAgreementAccepted(AgreementAccepted $event): void
    {
        $payment = $this->paymentFactory->create($event->getTenantId(), $event->getOwnerId(), $event->getDays(), $event->getPrice());
        $payment->pay($this->publisher, $this->paymentService);
    }
}