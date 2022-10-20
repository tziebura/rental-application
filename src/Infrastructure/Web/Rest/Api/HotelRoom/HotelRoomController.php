<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoom;

use App\Application\HotelRoom\HotelRoomApplicationService;
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
    private HotelRoomApplicationService $hotelRoomApplicationService;
    private HotelRoomReadModel $hotelRoomReadModel;
    private SerializerInterface $serializer;

    public function __construct(HotelRoomApplicationService $hotelRoomApplicationService, HotelRoomReadModel $hotelRoomReadModel, SerializerInterface $serializer)
    {
        $this->hotelRoomApplicationService = $hotelRoomApplicationService;
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
        $this->hotelRoomApplicationService->add(
            $dto->getHotelId(),
            $dto->getNumber(),
            $dto->getDescription(),
            $dto->getRooms()
        );

        return new JsonResponse([], Response::HTTP_CREATED);
    }

    /**
     * @Route(path="/book/{id}", name="book", methods={"PUT"})
     */
    public function book(string $id, HotelRoomBookingDto $bookingDto): Response
    {
        $this->hotelRoomApplicationService->book(
            $id,
            $bookingDto->getDays(),
            $bookingDto->getTenantId()
        );

        return new JsonResponse([], Response::HTTP_OK);
    }
}