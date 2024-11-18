<?php

namespace App\Message;

use App\Entity\User;

class SecretSantaMessage
{
    private User $giver;
    private User $receiver;

    public function __construct(User $from, User $receiver) {
        $this->giver = $from;
        $this->receiver = $receiver;
    }

    public function getGiver(): User
    {
        return $this->giver;
    }

    public function getReceiver(): User
    {
        return $this->receiver;
    }
}