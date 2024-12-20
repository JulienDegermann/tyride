<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Symfony\Component\Mime\Email;

interface AddParticipantNotifierServiceInterface
{
    public function __invoke(Ride $ride, User $participant): Email;
}