<?php

namespace App\Tests\Infrastructure\Persistence\Sql\Apartment;

use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ApartmentFactory;
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
    private ApartmentFactory $factory;

    public function setUp(): void
    {
        $this->subject = new SqlApartmentRepository(
            $this->getContainer()->get(DoctrineOrmApartmentRepository::class)
        );

        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
        $this->factory = new ApartmentFactory();
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
        $apartment = $this->factory->create("Florianska", "98-765", "12", "34", "Krakow", "Poland", ["Room1" => 50.0], "1234", "The greatest apartment");
        $this->subject->save($apartment);
        $apartment = $this->factory->create("Florianska", "98-999", "10", "42", "Krakow", "Poland", ["Room42" => 100.0], "5692", "Great apartment");
        $this->subject->save($apartment);
        $apartment = $this->factory->create("Florianska", "98-123", "11", "13", "Krakow", "Poland", ["Room13" => 30.0], "2083", "Not so bad apartment");
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
        return $this->factory->create(
            self::STREET,
            self::POSTAL_CODE,
            self::HOUSE_NUMBER,
            self::APARTMENT_NUMBER,
            self::CITY,
            self::COUNTRY,
            self::ROOMS_DEFINITION,
            self::OWNER_ID,
            self::DESCRIPTION
        );
    }
}
