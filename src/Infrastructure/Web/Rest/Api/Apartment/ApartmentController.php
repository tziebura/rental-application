<?php

namespace App\Infrastructure\Web\Rest\Api\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use App\Query\Apartment\ApartmentReadModel;
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
    private ApartmentReadModel $apartmentReadModel;

    public function __construct(ApartmentApplicationService $apartmentApplicationService)
    {
        $this->apartmentApplicationService = $apartmentApplicationService;
    }

    /**
     * @Route(path="/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $apartments = $this->apartmentReadModel->findAll();
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

    /**
     * @Route(path="/book/{id}", name="book", methods={"PUT"})
     */
    public function book(string $id, ApartmentBookingDto $apartmentBookingDto): Response {
        $this->apartmentApplicationService->book(
            $id,
            $apartmentBookingDto->getTenantId(),
            $apartmentBookingDto->getStart(),
            $apartmentBookingDto->getEnd()
        );

        return new JsonResponse([], Response::HTTP_OK);
    }
}