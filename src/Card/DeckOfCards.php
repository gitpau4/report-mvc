<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Represents a deck of 52 cards.
 *
 * @autor Paula Frölander, pafo24
 */
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

    /**
     * Builds the deck of cards with 13 different values and 4 different suits.
     */
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
     * Returns array with cards, the deck.
     *
     * @return array<CardGraphic>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Returns deck in the original order.
     *
     * @return array<CardGraphic>
     */
    public function getOriginalDeck(): array
    {
        return $this->originalDeck;
    }

    /**
     * Shuffles the cards in the deck.
     */
    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    /**
     * Resets the deck to its original order.
     */
    public function reset(): void
    {
        $this->deck = $this->originalDeck;
    }

    /**
     * Draws the last card from the deck and returns it.
     */
    public function drawCard(): ?CardGraphic
    {
        return array_pop($this->deck);
    }

    /**
     * Draws given number of cards from deck and returns them in an array.
     *
     * @return array<CardGraphic>
     */
    public function drawNumberCards(int $number): array
    {
        if ($number > $this->getNumberOfCards()) {
            return [];
        }

        $drawedCards = [];
        for ($i = 0; $i < $number; $i++) {
            $card = array_pop($this->deck);

            if ($card !== null) {
                $drawedCards[] = $card;
            }
        }

        return $drawedCards;
    }

    /**
     * Returns amount of cards left in the deck.
     *
     * @return int
     */
    public function getNumberOfCards(): int
    {
        return count($this->deck);
    }
}
