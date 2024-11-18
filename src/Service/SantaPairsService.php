<?php

namespace App\Service;

use App\Message\SecretSantaMessage;
use App\Model\UserModel;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class SantaPairsService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserModel $userModel,
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
    public function generatePairs(): void
    {
        if (!$this->canGenerate()) {
            throw new BadRequestException('Not enough users');
        }

        $this->userRepository->setReceiverNull();

        $userIds = $this->userRepository->getIds();
        shuffle($userIds);
        $count = count($userIds);
        foreach ($userIds as $key => $userId) {
            $giver = $this->userRepository->find($userId);
            $receiver = $this->userRepository->find($userIds[($key+1) % $count]);

            $giver->setReceiver($receiver);
            $this->userModel->save($giver);
        }
    }

    /**
     * @throws Exception
     */
    public function validatePairs(): void
    {
        $count = $this->userRepository->getCountWithoutReceiver();

        if ($count > 0) {
            throw new Exception('Some users without a receiver');
        }
    }

    public function sendMessages(): void
    {
        $limit = 50;
        $offset = 0;

        $users = $this->userRepository->findPartialUsers($limit, $offset);
        while (!empty($users)) {
            foreach ($users as $user) {
                $this->bus->dispatch(new SecretSantaMessage($user, $user->getReceiver()));
            }

            $offset += count($users);
            $users = $this->userRepository->findPartialUsers($limit, $offset);
        }
    }
}