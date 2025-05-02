<?php

namespace App\Card;

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
