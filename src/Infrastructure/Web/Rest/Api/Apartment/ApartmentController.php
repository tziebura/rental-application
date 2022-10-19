<?php

namespace App\Infrastructure\Web\Rest\Api\Apartment;

use App\Application\Apartment\ApartmentApplicationService;
use App\Query\Apartment\ApartmentReadModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/rest/v1/apartment", name="api_v1_apartment_")
 */
class ApartmentController extends AbstractController
{
    private ApartmentApplicationService $apartmentApplicationService;
    private ApartmentReadModel $apartmentReadModel;
    private SerializerInterface $serializer;

    public function __construct(ApartmentApplicationService $apartmentApplicationService, ApartmentReadModel $apartmentReadModel, SerializerInterface $serializer)
    {
        $this->apartmentApplicationService = $apartmentApplicationService;
        $this->apartmentReadModel = $apartmentReadModel;
        $this->serializer = $serializer;
    }

    /**
     * @Route(path="/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $apartments = $this->apartmentReadModel->findAll();
        return new Response($this->serializer->serialize($apartments, 'json'), Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route(path="/{id}", name="get", methods={"GET"})
     */
    public function get(string $id): Response
    {
        $apartment = $this->apartmentReadModel->findById($id);

        if (!$apartment) {
            throw new NotFoundHttpException('Apartment not found.');
        }

        return new Response($this->serializer->serialize($apartment, 'json'), Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
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
    public function book(string $id, ApartmentBookingDTO $apartmentBookingDto): Response {
        $this->apartmentApplicationService->book(
            $id,
            $apartmentBookingDto->getTenantId(),
            $apartmentBookingDto->getStart(),
            $apartmentBookingDto->getEnd()
        );

        return new JsonResponse([], Response::HTTP_OK);
    }
}