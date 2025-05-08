<?php

namespace App\Card;

use App\Card\DeckOfCards;
use App\Card\Player;

class GameLogic
{
    private DeckOfCards $deck;
    private Player $player;
    private Player $bank;
    private int $limit = 21;

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

    public function playerDraw(): int
    {
        $card = $this->deck->drawCard();
        $playerHand = $this->player->getHand();
        $playerHand->addCard($card);
        return $this->player->getPoints();
    }

    public function bankDraw(): int
    {
        $bankHand = $this->bank->getHand();

        while ($this->bank->getPoints() < 17) {
            $card = $this->deck->drawCard();
            $bankHand->addCard($card);
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
}
