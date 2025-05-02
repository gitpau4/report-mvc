<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /** @var array<Card> */
    private array $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * @return array<Card>
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    public function getNumberOfCards(): int
    {
        return count($this->hand);
    }

    /**
     * @return array<string>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /**
     * @return array<string>
     */
    public function getSuits(): array
    {
        $suits = [];
        foreach ($this->hand as $card) {
            $suits[] = $card->getSuit();
        }
        return $suits;
    }
}
