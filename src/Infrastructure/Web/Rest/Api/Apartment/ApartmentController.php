<?php

namespace App\Infrastructure\Web\Rest\Api\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/apartment", name="api_v1_apartment_")
 */
class ApartmentController extends AbstractController
{
    private ApartmentApplicationService $apartmentApplicationService;

    public function __construct(ApartmentApplicationService $apartmentApplicationService)
    {
        $this->apartmentApplicationService = $apartmentApplicationService;
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(ApartmentDTO $dto): Response
    {
        $this->apartmentApplicationService->add(
            $dto->getOwnerId(),
            $dto->getStreet(),
            $dto->getPostalCode(),
            $dto->getHouseNumber(),
            $dto->getApartmentNumber(),
            $dto->getCity(),
            $dto->getCountry(),
            $dto->getDescription(),
            $dto->getRoomsDefinition()
        );

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}