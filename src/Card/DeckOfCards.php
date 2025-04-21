<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    private $deck = [];
    private $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    private $suits = ['spades', 'hearts', 'clubs', 'diamonds'];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->deck[] = new CardGraphic($value, $suit);
            }
        }
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function reset(): void
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->deck[] = new CardGraphic($value, $suit);
            }
        }
    }

    public function drawCard(): CardGraphic
    {
        return array_pop($this->deck);
    }

    public function drawNumberCards(int $number): array
    {
        $drawedCards = [];
        for ($i=0; $i < $number; $i++) { 
            $drawedCards[] = array_pop($this->deck);
        }
        return $drawedCards;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }
}