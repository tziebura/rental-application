<?php

namespace App\Infrastructure\Web\Rest\Api\HotelRoomOffer;

use App\Application\HotelRoomOffer\HotelRoomOfferDTO;
use App\Application\HotelRoomOffer\HotelRoomOfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/hotel-room-offer", name="api_v1_hotel_room_offer_")
 */
class HotelRoomOfferController extends AbstractController
{
    private HotelRoomOfferService $hotelRoomOfferService;

    public function __construct(HotelRoomOfferService $hotelRoomOfferService)
    {
        $this->hotelRoomOfferService = $hotelRoomOfferService;
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(HotelRoomOfferDTO $dto): Response
    {
        $this->hotelRoomOfferService->add($dto);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}