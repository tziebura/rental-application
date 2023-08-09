<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\Hotel;

use App\Infrastructure\Persistence\Sql\Hotel\DoctrineOrmHotelRepository;
use App\Tests\Infrastructure\Fixtures\Hotel\HotelWithoutRoomsFixture;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class HotelControllerSystemTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private AbstractBrowser $browser;
    private DoctrineOrmHotelRepository $hotelRepository;

    public function setUp(): void
    {
        $this->browser = $this->createClient([], [
            'HTTP_HOST' => 'localhost:8080',
        ]);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
        $this->hotelRepository = $this->getContainer()->get(DoctrineOrmHotelRepository::class);
    }

    /**
     * @test
     */
    public function shouldCreateHotelAndReturn201StatusCode(): void
    {
        $this->databaseTool->loadFixtures([]);
        $hotelRequest = json_decode(file_get_contents(__DIR__ . '/HotelControllerSystemTest/create_hotel_request.json'), true);
        $this->browser->jsonRequest('POST', '/rest/v1/hotel/', $hotelRequest);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->browser);
        $this->assertEquals(1, $this->hotelRepository->count([]));
    }

    /**
     * @test
     */
    public function shouldReturnHotelsList(): void
    {
        $this->databaseTool->loadFixtures([HotelWithoutRoomsFixture::class]);
        $this->browser->jsonRequest('GET', '/rest/v1/hotel/');

        $actualResponse = $this->browser->getResponse()->getContent();
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/HotelControllerSystemTest/index_response.json',
            $actualResponse
        );
    }
}