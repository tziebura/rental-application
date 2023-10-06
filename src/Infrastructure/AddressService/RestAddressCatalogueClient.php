<?php

namespace App\Infrastructure\AddressService;

use App\Domain\Address\AddressCatalogue;
use App\Domain\Address\AddressDto;
use App\Domain\Address\AddressVerification;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RestAddressCatalogueClient implements AddressCatalogue
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
    public function exists(AddressDto $addressDto): bool
    {
        $request = new Request('POST', $this->url . '/rest/v1/address/verify', [], $this->serializer->serialize($addressDto, 'json'));

        $response = $this->client->sendRequest($request);
        /** @var AddressVerification $verification */
        $verification = $this->serializer->deserialize($response->getBody()->getContents(), AddressVerification::class, 'json');

        return $verification->isValid();
    }
}