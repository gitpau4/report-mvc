<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /** @var array<CardGraphic> */
    private array $deck = [];

    /** @var array<CardGraphic> */
    private array $originalDeck = [];

    /** @var array<string> */
    private array $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

    /** @var array<string> */
    private array $suits = ['spades', 'hearts', 'clubs', 'diamonds'];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                $this->deck[] = new CardGraphic($value, $suit);
            }
        }

        $this->originalDeck = $this->deck;
    }

    /**
     * @return array<CardGraphic>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * @return array<CardGraphic>
     */
    public function getOriginalDeck(): array
    {
        return $this->originalDeck;
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function reset(): void
    {
        $this->deck = $this->originalDeck;
    }

    public function drawCard(): ?CardGraphic
    {
        return array_pop($this->deck);
    }

    /**
     * @return array<CardGraphic>
     */
    public function drawNumberCards(int $number): array
    {
        $drawedCards = [];
        if ($number <= $this->getNumberOfCards()) {
            for ($i = 0; $i < $number; $i++) {
                $card = array_pop($this->deck);
                if ($card !== null) {
                    $drawedCards[] = $card;
                }
            }
        }

        return $drawedCards;
    }

    public function getNumberOfCards(): int
    {
        return count($this->deck);
    }
}
