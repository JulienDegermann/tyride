<?php

namespace App\Infrastructure\Service\NotifierService;

class NotifierConfig implements NotifierConfigInterface
{
    private string $from;

    private string $signature;

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function __construct()
    {
        $this->from = "no-reply.blablavelo@julien-degermann.fr";
        $this->signature = "\n \n Julien, Team Blabla Vélo 🚴‍♂️🚴🚴‍♀️";
    }
}