<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Represents a hand of cards.
 *
 * @autor Paula Frölander, pafo24
 */
class CardHand
{
    /** @var array<CardGraphic> */
    private array $hand = [];

    public function addCard(CardGraphic $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * @return array<CardGraphic>
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

        $cardValues = [
            'J' => 11,
            'Q' => 12,
            'K' => 13,
            'A' => 14,
        ];

        foreach ($this->getValues() as $value) {
            $valToAdd = $cardValues[$value] ?? intval($value);

            if ($value === 'A' && $points > 7) {
                $valToAdd = 1;
            }

            $points += $valToAdd;
        }

        return $points;
    }
}
