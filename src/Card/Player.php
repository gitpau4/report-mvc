<?php

namespace App\Card;

use App\Card\CardHand;

class Player
{
    private string $name;
    private CardHand $hand;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->hand = new CardHand();
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPoints(): int
    {
        return $this->hand->getPoints();
    }
}
