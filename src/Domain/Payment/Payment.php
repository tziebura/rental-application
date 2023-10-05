<?php

namespace App\Domain\Payment;

class Payment
{

    private string $senderId;
    private string $recipientId;
    private float $totalAmount;

    public function __construct(string $senderId, string $recipientId, float $totalAmount)
    {
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->totalAmount = $totalAmount;
    }

    public function pay(PaymentEventsPublisher $publisher, PaymentService $paymentService): void
    {
        $status = $paymentService->transfer($this->senderId, $this->recipientId, $this->totalAmount);

        if ($status === PaymentStatus::SUCCESS) {
            $publisher->publishPaymentCompleted($this->senderId, $this->recipientId, $this->totalAmount);
        } else {
            $publisher->publishPaymentFailed($this->senderId, $this->recipientId, $this->totalAmount);
        }
    }
}