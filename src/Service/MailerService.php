<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

readonly class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger
    ) {
    }

    public function sendSecretSanta(User $giver, User $receiver): void
    {
        $email = (new Email())
            ->from('admin@example.com')
            ->to($giver->getEmail())
            ->subject('Secret Santa')
            ->text(sprintf(
                "Give a present to %s %s %s",
                $receiver->getLastName(), $receiver->getFirstName(), $receiver->getMiddleName()
            ))
        ;

        $this->send($email);
    }

    private function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
            $this->logger->info(sprintf(
                'Mailer Success: to: %s',
                $email->getTo()[0]->getAddress()
            ));
        } catch (\Exception|TransportExceptionInterface $exception) {
            $this->logger->error('Mailer Error: ' . $exception->getMessage());
        }
    }
}