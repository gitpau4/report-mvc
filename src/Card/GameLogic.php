<?php

namespace App\Card;

use App\Card\DeckOfCards;
use App\Card\Player;

/**
 * Handles game logic for game 21.
 * 
 * @autor Paula Frölander, pafo24
 */
class GameLogic
{
    private DeckOfCards $deck;
    private Player $player;
    private Player $bank;
    private int $limit = 21;
    private bool $isGameOver = false;

    public function __construct()
    {
        $this->deck = new DeckOfCards();
        $this->player = new Player("Spelare");
        $this->bank = new Player("Bank");
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBank(): Player
    {
        return $this->bank;
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }

    public function shuffleDeck(): void
    {
        $this->deck->shuffle();
    }

    public function playerDraw(): int
    {
        $card = $this->deck->drawCard();
        $playerHand = $this->player->getHand();
        if ($card !== null) {
            $playerHand->addCard($card);
        }
        return $this->player->getPoints();
    }

    public function bankDraw(): int
    {
        $bankHand = $this->bank->getHand();

        while ($this->bank->getPoints() < 17) {
            $card = $this->deck->drawCard();
            if ($card !== null) {
                $bankHand->addCard($card);
            }
        }

        return $this->bank->getPoints();
    }

    public function isOverLimit(int $points): bool
    {
        return $points > $this->limit;
    }

    public function getWinner(): string
    {
        $playerPoints = $this->player->getPoints();
        $bankPoints = $this->bank->getPoints();

        if ($this->isOverLimit($bankPoints)) {
            return $this->player->getName();
        } elseif ($bankPoints >= $playerPoints) {
            return $this->bank->getName();
        }

        return $this->player->getName();
    }

    public function setGameOver(): void
    {
        $this->isGameOver = true;
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }
}
