<?php

namespace App\Card;

/**
 * Represents a card.
 *
 * @autor Paula Frölander, pafo24
 */
class Card
{
    protected string $value;
    protected string $suit;

    public function __construct(string $value, string $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }
}
