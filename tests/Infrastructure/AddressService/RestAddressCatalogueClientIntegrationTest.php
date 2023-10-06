<?php

namespace App\Tests\Infrastructure\AddressService;

use App\Contracts\AddressVerification\AddressVerificationContract;
use App\Contracts\AddressVerification\AddressVerificationScenario;
use App\Domain\Address\AddressDto;
use App\Infrastructure\AddressService\RestAddressCatalogueClient;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class RestAddressCatalogueClientIntegrationTest extends WebTestCase
{

    private RestAddressCatalogueClient $subject;
    private AddressVerificationContract $addressVerificationContract;

    public function setUp(): void
    {
        $this->bootKernel();
        $this->subject = $this->getContainer()->get(RestAddressCatalogueClient::class);
        $this->addressVerificationContract = new AddressVerificationContract;
    }

    /**
     * @test
     */
    public function shouldRecognizeValidAddress(): void
    {
        $scenario = $this->addressVerificationContract->validAddress();
        $dto      = $this->addressDto($scenario);

        $actual = $this->subject->exists($dto);
        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function shouldRecognizeInvalidAddress(): void
    {
        $scenario = $this->addressVerificationContract->invalidAddress();
        $dto      = $this->addressDto($scenario);

        $actual = $this->subject->exists($dto);
        $this->assertFalse($actual);
    }

    private function addressDto(AddressVerificationScenario $scenario): AddressDto
    {
        $request = $scenario->getRequest();
        return new AddressDto(
            $request->getStreet(),
            $request->getBuildingNumber(),
            $request->getPostalCode(),
            $request->getCity(),
            $request->getCountry()
        );
    }
}
