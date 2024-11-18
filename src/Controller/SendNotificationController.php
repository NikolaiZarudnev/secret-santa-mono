<?php

namespace App\Controller;

use App\Service\SantaPairsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SendNotificationController extends AbstractController
{
    public function __construct(
        private readonly SantaPairsService $santaPairsService,
    ) {
    }

    #[Route('/send-secret-santa-message', name: 'send_secret_santa_message')]
    public function sendSecretSanta(): JsonResponse
    {
        try {
            $this->santaPairsService->generateNumbers();
        } catch (BadRequestException $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                data: ['error' => $e->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->santaPairsService->sendMessages();

        return new JsonResponse(status: Response::HTTP_OK);
    }
}
