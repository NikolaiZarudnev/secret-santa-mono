<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Model\UserModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UserModel $userModel,
    ) {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $userDto = $this->serializer->deserialize(
            $request->getContent(),
            UserDTO::class,
            'json'
        );

        $user = $this->userModel->create($userDto);
        $this->userModel->save($user);

        return new JsonResponse(status: Response::HTTP_OK);
    }
}
