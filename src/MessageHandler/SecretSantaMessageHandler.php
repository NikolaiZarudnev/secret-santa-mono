<?php

namespace App\MessageHandler;

use App\Message\SecretSantaMessage;
use App\Service\MailerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SecretSantaMessageHandler
{
    public function __construct(
        private MailerService $mailerService
    ) {
    }

    public function __invoke(SecretSantaMessage $message): void
    {
        $this->mailerService->sendSecretSanta($message->getGiver(), $message->getReceiver());
    }
}