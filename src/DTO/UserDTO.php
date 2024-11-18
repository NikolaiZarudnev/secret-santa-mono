<?php

namespace App\DTO;

readonly class UserDTO implements UserDTOInterface
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $middleName,
        private string $email,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}