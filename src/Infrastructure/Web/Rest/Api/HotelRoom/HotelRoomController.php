<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoom;

use App\Application\Hotel\HotelApplicationService;
use App\Application\Hotel\HotelRoomBookingDTO;
use App\Application\Hotel\HotelRoomDTO;
use App\Query\HotelRoom\HotelRoomReadModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/rest/v1/hotel-room", name="api_v1_hotel_room_")
 */
class HotelRoomController extends AbstractController
{
    private HotelApplicationService $hotelApplicationService;
    private HotelRoomReadModel $hotelRoomReadModel;
    private SerializerInterface $serializer;

    public function __construct(HotelApplicationService $hotelApplicationService, HotelRoomReadModel $hotelRoomReadModel, SerializerInterface $serializer)
    {
        $this->hotelApplicationService = $hotelApplicationService;
        $this->hotelRoomReadModel = $hotelRoomReadModel;
        $this->serializer = $serializer;
    }

    /**
     * @Route(path="/hotel/{hotelId}", name="get_by_hotel", methods={"GET"})
     */
    public function index(string $hotelId): Response
    {
        $hotelRooms = $this->hotelRoomReadModel->findByHotel($hotelId);

        return new Response($this->serializer->serialize($hotelRooms, 'json'), Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(HotelRoomDTO $dto): Response
    {
        $this->hotelApplicationService->addHotelRoom($dto);

        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/book/{id}", name="book", methods={"PUT"})
     */
    public function book(string $id, HotelRoomBookingDto $bookingDto): Response
    {
        $this->hotelApplicationService->book($bookingDto);

        return new JsonResponse([], Response::HTTP_OK);
    }
}