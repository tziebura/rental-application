<?php

namespace App\Tests\Infrastructure\Web\Rest\Api\HotelRoom;

use App\Domain\Booking\Booking;
use App\Domain\Hotel\HotelRoom;
use App\Tests\Domain\Booking\BookingAssertion;
use App\Tests\Infrastructure\Fixtures\Hotel\HotelWithoutRoomsFixture;
use App\Tests\Infrastructure\Fixtures\Hotel\HotelWithRoomsFixture;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;

class HotelRoomControllerSystemTest extends WebTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private AbstractBrowser $browser;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $this->browser = $this->createClient([], [
            'HTTP_HOST' => 'localhost:8080',
        ]);
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager = $this->getContainer()->get(EntityManagerInterface::class);
    }

    /**
     * @test
     */
    public function shouldCreateHotelRoomForGivenHotelAndReturn201StatusCode(): void
    {
        $this->databaseTool->loadFixtures([HotelWithoutRoomsFixture::class]);
        $hotelRoomRequest = json_decode(file_get_contents(__DIR__ . '/HotelRoomControllerSystemTest/create_hotel_room_request.json'), true);
        $this->browser->jsonRequest('POST', '/rest/v1/hotel-room/', $hotelRoomRequest);

        $this->assertStatusCode(Response::HTTP_CREATED, $this->browser);
        $this->assertEquals(1, $this->entityManager->getRepository(HotelRoom::class)->count([]));
    }

    /**
     * @test
     */
    public function shouldBookHotelRoom(): void
    {
        $this->databaseTool->loadFixtures([HotelWithRoomsFixture::class]);
        $bookRequest = json_decode(file_get_contents(__DIR__ . '/HotelRoomControllerSystemTest/book_request.json'), true);
        $this->browser->jsonRequest('PUT', '/rest/v1/hotel-room/book/1', $bookRequest);

        $this->assertStatusCode(Response::HTTP_OK, $this->browser);
        $actualBooking = $this->entityManager->getRepository(Booking::class)->findAll()[0];
        BookingAssertion::assertThat($actualBooking)
            ->hasRentalPlaceIdEqualTo(1)
            ->isHotelRoomBooking()
            ->isOpen();
    }
}