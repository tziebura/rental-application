<?php

namespace App\Infrastructure\Web\Rest\Api\ApartmentOffer;

use App\Application\ApartmentOffer\ApartmentOfferDTO;
use App\Application\ApartmentOffer\ApartmentOfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/apartment-offer", name="api_v1_apartment_offer_")
 */
class ApartmentOfferController extends AbstractController
{
    private ApartmentOfferService $apartmentOfferService;

    public function __construct(ApartmentOfferService $apartmentOfferService)
    {
        $this->apartmentOfferService = $apartmentOfferService;
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(ApartmentOfferDTO $dto): Response
    {
        $this->apartmentOfferService->add($dto);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}