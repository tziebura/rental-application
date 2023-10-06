<?php

namespace App\Contracts\Payment;

class PaymentRequest
{
    private string $senderId;
    private string $recipientId;
    private float $amount;

    public function __construct(string $senderId, string $recipientId, float $amount)
    {
        $this->senderId = $senderId;
        $this->recipientId = $recipientId;
        $this->amount = $amount;
    }

    public function serialize(): string
    {
        $data = [
            'sender_id' => $this->senderId,
            'recipient_id' => $this->recipientId,
            'amount' => $this->amount,
        ];

        return json_encode($data);
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}