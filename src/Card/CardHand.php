<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /** @var array<Card> */
    private array $hand = [];

    public function addCard(Card $card): void
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

    public function getPoints(): int
    {
        $points = 0;

        foreach ($this->getValues() as $value) {
            $valToAdd = $value; // initierar med värdet för att undivka else sats

            if ($value === 'A') {
                $valToAdd = 14;
                if ($points > 7) {
                    $valToAdd = 1;
                }
            } elseif ($value === 'J') {
                $valToAdd = 11;
            } elseif ($value === 'Q') {
                $valToAdd = 12;
            } elseif ($value === 'K') {
                $valToAdd = 13;
            }

            $points += intval($valToAdd);
        }

        return $points;
    }
}
