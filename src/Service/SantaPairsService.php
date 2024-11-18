<?php

namespace App\Service;

use App\Message\SecretSantaMessage;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class SantaPairsService
{
    public function __construct(
        private UserRepository $userRepository,
        private MessageBusInterface $bus,
    ) {
    }

    public function canGenerate(): bool
    {
        $count = $this->userRepository->getCount();

        return $count > 3;
    }

    /**
     * @throws BadRequestException
     */
    public function generateNumbers(): void
    {
        if (!$this->canGenerate()) {
            throw new BadRequestException('Not enough users');
        }

        $this->userRepository->allSetRandomNumber();
    }

    public function sendMessages(): void
    {
        $limit = 50;
        $offset = 0;

        $users = $this->userRepository->findPartialOrderBySerialNumber($limit, $offset);
        $count = count($users);
        while (!empty($users)) {
            foreach ($users as $key => $user) {
                $this->bus->dispatch(new SecretSantaMessage($user, $users[($key + 1) % $count]));
            }

            $offset += $count;
            $users = $this->userRepository->findPartialOrderBySerialNumber($limit, $offset);
            $count = count($users);
        }
    }
}