<?php

namespace App\Infrastructure\Web\Rest\Api\User;

use App\Application\User\UserApplicationService;
use App\Application\User\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/rest/v1/user", name="api_v1_user_")
 */
class UserController extends AbstractController
{
    private UserApplicationService $userService;

    public function __construct(UserApplicationService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route(path="/", name="create", methods={"POST"})
     */
    public function post(UserDTO $dto): Response
    {
        $this->userService->register($dto);
        return new JsonResponse([], Response::HTTP_CREATED);
    }
}