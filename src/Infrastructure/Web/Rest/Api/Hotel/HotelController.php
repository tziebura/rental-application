<?php

namespace App\Infrastructure\Web\Rest\Api\Hotel;

use App\Application\Hotel\HotelApplicationService;
use App\Query\Hotel\HotelReadModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/rest/v1/hotel", name="api_v1_hotel_")
 */
class HotelController extends AbstractController
{
    private HotelApplicationService $hotelApplicationService;
    private HotelReadModel $hotelReadModel;
    private SerializerInterface $serializer;

    public function __construct(HotelApplicationService $hotelApplicationService, HotelReadModel $hotelReadModel, SerializerInterface $serializer)
    {
        $this->hotelApplicationService = $hotelApplicationService;
        $this->hotelReadModel = $hotelReadModel;
        $this->serializer = $serializer;
    }

    /**
     * @Route(path="/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $hotels = $this->hotelReadModel->findAll();
        return new Response($this->serializer->serialize($hotels, 'json'), Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
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