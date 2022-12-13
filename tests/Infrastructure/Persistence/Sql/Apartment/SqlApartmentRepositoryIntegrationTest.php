<?php

namespace App\Tests\Infrastructure\Persistence\Sql\Apartment;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentBuilder;
use App\Infrastructure\Persistence\Sql\Apartment\DoctrineOrmApartmentRepository;
use App\Infrastructure\Persistence\Sql\Apartment\SqlApartmentRepository;
use App\Tests\Domain\Apartment\ApartmentAssertion;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class SqlApartmentRepositoryIntegrationTest extends WebTestCase
{
    private const EXISTING_ID = '1';
    private const STREET = 'street';
    private const HOUSE_NUMBER = '1';
    private const POSTAL_CODE = '1-2';
    private const APARTMENT_NUMBER = '1';
    private const CITY = 'city';
    private const COUNTRY = 'country';
    private const ROOMS_DEFINITION = [
        'room1' => 10.0,
        'room2' => 20.5,
    ];
    private const OWNER_ID = '1';
    private const DESCRIPTION = 'description';

    private SqlApartmentRepository $subject;
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        $this->subject = new SqlApartmentRepository(
            $this->getContainer()->get(DoctrineOrmApartmentRepository::class)
        );

        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * @test
     */
    public function shouldReturnExistingApartment()
    {
        $this->databaseTool->loadFixtures([]);

        $apartment = $this->createApartment();
        $this->subject->save($apartment);

        $actual = $this->subject->findById(self::EXISTING_ID);

        ApartmentAssertion::assertThat($actual)
            ->hasOwnerEqualTo(self::OWNER_ID)
            ->hasDescriptionEqualTo(self::DESCRIPTION)
            ->hasAddressEqualTo(self::STREET, self::POSTAL_CODE, self::HOUSE_NUMBER, self::APARTMENT_NUMBER, self::CITY, self::COUNTRY)
            ->hasRoomsEqualTo(self::ROOMS_DEFINITION);
    }

    /**
     * @test
     */
    public function shouldReturnExistingApartmentWeWant()
    {
        $apartment = ApartmentBuilder::create()
            ->withStreet("Florianska")
            ->withPostalCode("98-765")
            ->withHouseNumber("12")
            ->withApartmentNumber("34")
            ->withCity("Krakow")
            ->withCountry("Poland")
            ->withRoomsDefinition(["Room1" => 50.0])
            ->withOwnerId("1234")
            ->withDescription("The greatest apartment")
            ->build();
        $this->subject->save($apartment);
        $apartment = ApartmentBuilder::create()
            ->withStreet("Florianska")
            ->withPostalCode("98-999")
            ->withHouseNumber("10")
            ->withApartmentNumber("42")
            ->withCity("Krakow")
            ->withCountry("Poland")
            ->withRoomsDefinition(["Room42" => 100.0])
            ->withOwnerId("5692")
            ->withDescription("Great apartment")
            ->build();
        $this->subject->save($apartment);
        $apartment = ApartmentBuilder::create()
            ->withStreet("Florianska")
            ->withPostalCode("98-123")
            ->withHouseNumber("11")
            ->withApartmentNumber("13")
            ->withCity("Krakow")
            ->withCountry("Poland")
            ->withRoomsDefinition(["Room13" => 30.0])
            ->withOwnerId("2083")
            ->withDescription("Not so bad apartment")
            ->build();
        $this->subject->save($apartment);

        $actual = $this->subject->findById(self::EXISTING_ID);

        ApartmentAssertion::assertThat($actual)
            ->hasOwnerEqualTo(self::OWNER_ID)
            ->hasDescriptionEqualTo(self::DESCRIPTION)
            ->hasAddressEqualTo(self::STREET, self::POSTAL_CODE, self::HOUSE_NUMBER, self::APARTMENT_NUMBER, self::CITY, self::COUNTRY)
            ->hasRoomsEqualTo(self::ROOMS_DEFINITION);
    }

    private function createApartment(): Apartment
    {
        return ApartmentBuilder::create()
            ->withStreet(self::STREET)
            ->withPostalCode(self::POSTAL_CODE)
            ->withHouseNumber(self::HOUSE_NUMBER)
            ->withApartmentNumber(self::APARTMENT_NUMBER)
            ->withCity(self::CITY)
            ->withCountry(self::COUNTRY)
            ->withRoomsDefinition(self::ROOMS_DEFINITION)
            ->withOwnerId(self::OWNER_ID)
            ->withDescription(self::DESCRIPTION)
            ->build();
    }
}
