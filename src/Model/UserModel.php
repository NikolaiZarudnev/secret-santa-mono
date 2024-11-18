<?php

namespace App\Model;

use App\DTO\UserDTOInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserModel
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(UserDTOInterface $userDTO): User
    {
        $user = new User();

        $user
            ->setEmail($userDTO->getEmail())
            ->setFirstName($userDTO->getFirstName())
            ->setLastName($userDTO->getLastName())
            ->setMiddleName($userDTO->getMiddleName())
        ;

        return $user;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}