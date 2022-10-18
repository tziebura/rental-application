<?php

namespace App\Infrastructure\Web\Rest\Api\Booking;

use App\Application\Booking\AcceptBooking;
use App\Application\Booking\RejectBooking;
use App\Application\CommandBus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/booking", name="api_v1_booking_")
 */
class BookingController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route(path="/reject/{id}", name="reject", methods={"PUT"})
     */
    public function reject(string $id): Response
    {
        $command = new RejectBooking($id);
        $this->commandBus->dispatch($command);
        return new JsonResponse();
    }

    /**
     * @Route(path="/accept/{id}", name="accept", methods={"PUT"})
     */
    public function accept(string $id): Response
    {
        $command = new AcceptBooking($id);
        $this->commandBus->dispatch($command);
        return new JsonResponse();
    }
}