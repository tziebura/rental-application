<?php

namespace App\Tests\Infrastructure\AddressService;

use App\Domain\Address\AddressDto;
use App\Infrastructure\AddressService\RestAddressCatalogueClient;
use PHPUnit\Framework\TestCase;

class RestAddressCatalogueClientIntegrationTest extends TestCase
{
    private const STREET = "Pawia";
    private const BUILDING_NUMBER = "1";
    private const POSTAL_CODE = "31-154";
    private const CITY = "Cracow";
    private const COUNTRY = "Poland";

    private RestAddressCatalogueClient $subject;

    public function setUp(): void
    {
        $this->subject = new RestAddressCatalogueClient();
    }

    /**
     * @test
     */
    public function shouldAlwaysReturnTrue(): void
    {
        $actual = $this->subject->exists(new AddressDto(
            self::STREET,
            self::BUILDING_NUMBER,
            self::POSTAL_CODE,
            self::CITY,
            self::COUNTRY
        ));

        $this->assertTrue($actual);
    }
}
