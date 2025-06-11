<?php

namespace App\Card;

/**
 * A visual representation of a card.
 * 
 * @autor Paula Frölander, pafo24
 */
class CardGraphic extends Card
{
    /** @var array<string, string> */
    private array $symbols = [
        'spades' => '♠',
        'hearts' => '♥',
        'clubs' => '♣',
        'diamonds' => '♦',
    ];

    public function __construct(string $value, string $suit)
    {
        parent::__construct($value, $suit);
    }

    public function getAsString(): string
    {
        $symbol = $this->symbols[$this->suit] ?? '?';
        return "{$this->value} {$symbol}";
    }
}
