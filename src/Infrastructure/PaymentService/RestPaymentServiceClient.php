<?php

namespace App\Infrastructure\PaymentService;

use App\Domain\Payment\PaymentService;
use App\Domain\Payment\PaymentStatus;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RestPaymentServiceClient implements PaymentService
{
    private ClientInterface $client;
    private string $url;
    private SerializerInterface $serializer;

    public function __construct(ClientInterface $client, string $url, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->url = $url;
        $this->serializer = $serializer;
    }

    public function transfer(string $senderId, string $recipientId, float $amount): string
    {
        $request = new Request('POST', $this->url . '/rest/v1/payment/pay', [], json_encode([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'amount' => $amount
        ]));

        $response = $this->client->sendRequest($request);
        /** @var PaymentStatus $status */
        $status = $this->serializer->deserialize($response->getBody()->getContents(), PaymentStatus::class, 'json');

        return $status->getStatus();
    }
}