<?php

namespace App\Infrastructure\Web\Rest\Api\Hotel;

use App\Application\Hotel\HotelApplicationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/hotel", name="api_v1_hotel_")
 */
class HotelController
{
    private HotelApplicationService $hotelApplicationService;

    public function __construct(HotelApplicationService $hotelApplicationService)
    {
        $this->hotelApplicationService = $hotelApplicationService;
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(HotelDTO $dto): Response
    {
        $this->hotelApplicationService->add(
            $dto->getName(),
            $dto->getStreet(),
            $dto->getBuildingNumber(),
            $dto->getPostalCode(),
            $dto->getCity(),
            $dto->getCountry()
        );

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}