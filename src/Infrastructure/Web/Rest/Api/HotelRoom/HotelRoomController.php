<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoom;

use App\Application\HotelRoom\HotelRoomApplicationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/hotel-room", name="api_v1_hotel_room_")
 */
class HotelRoomController
{
    private HotelRoomApplicationService $hotelRoomApplicationService;

    public function __construct(HotelRoomApplicationService $hotelRoomApplicationService)
    {
        $this->hotelRoomApplicationService = $hotelRoomApplicationService;
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
}