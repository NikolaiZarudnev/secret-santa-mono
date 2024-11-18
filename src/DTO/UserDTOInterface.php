<?php

namespace App\DTO;

interface UserDTOInterface
{
    public function getFirstName(): ?string;
    public function getMiddleName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;
}