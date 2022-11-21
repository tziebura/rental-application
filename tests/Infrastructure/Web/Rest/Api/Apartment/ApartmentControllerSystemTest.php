<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\Apartment;

use App\Tests\Infrastructure\Web\Rest\Api\Apartment\Fixtures\ApartmentBookingHistoryFixture;
use App\Tests\Infrastructure\Web\Rest\Api\Apartment\Fixtures\ApartmentFixture;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class ApartmentControllerSystemTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private AbstractBrowser $browser;

    public function setUp(): void
    {
        $this->browser = $this->createClient([], [
            'HTTP_HOST' => 'localhost:8080',
        ]);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * @test
     */
    public function shouldReturn404WhenApartmentDoesNotExist()
    {
        $this->databaseTool->loadFixtures([]);

        $this->browser->jsonRequest('GET', '/rest/v1/apartment/1');
        $this->assertStatusCode(Response::HTTP_NOT_FOUND, $this->browser);

        $actualResponse = json_decode($this->browser->getResponse()->getContent(), true);
        $this->assertEquals('Apartment not found.', $actualResponse['detail']);
    }

    /**
     * @test
     */
    public function shouldReturnExistingApartment()
    {
        $this->databaseTool->loadFixtures([ApartmentFixture::class, ApartmentBookingHistoryFixture::class]);

        $this->browser->jsonRequest('GET', '/rest/v1/apartment/1');
        $this->assertStatusCode(Response::HTTP_OK, $this->browser);

        $actualResponse = $this->browser->getResponse()->getContent();
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(__DIR__ . '/Fixtures/response.json'),
            $actualResponse
        );
    }
}
