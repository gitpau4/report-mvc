<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameLogic.
 */
class GameLogicTest extends TestCase
{
    /**
     * Construct object and verify it is a GameLogic object.
     */
    public function testCreateObject()
    {
        $logic = new GameLogic();
        $this->assertInstanceOf("\App\Card\GameLogic", $logic);

        $playerName = $logic->getPlayer()->getName();
        $this->assertEquals("Spelare", $playerName);

        $bankName = $logic->getBank()->getName();
        $this->assertEquals("Bank", $bankName);
    }

    /**
     * Test shuffle method.
     */
    public function testShuffleDeck()
    {
        $logic = new GameLogic();
        $before = $logic->getDeck()->getDeck();
        $logic->shuffleDeck();
        $after = $logic->getDeck()->getDeck();
        $this->assertNotEquals($before, $after);
    }

    /**
     * Test draw methods.
     */
    public function testDraw()
    {
        $logic = new GameLogic();

        // player draw
        $playerHand = $logic->getPlayer()->getHand();
        $playerPoints = $logic->playerDraw();
        $this->assertEquals(1, $playerHand->getNumberOfCards());
        $this->assertNotEquals(0, $playerPoints);

        // bank draw
        $bankHand = $logic->getBank()->getHand();
        $bankPoints = $logic->bankDraw();
        $this->assertNotEquals(0, $bankHand->getNumberOfCards());
        $this->assertNotEquals(0, $bankPoints);
    }

    /**
     * Test limit method.
     */
    public function testLimit()
    {
        $logic = new GameLogic();

        $this->assertTrue($logic->isOverLimit(22));
        $this->assertFalse($logic->isOverLimit(21));
    }

    /**
     * Test get winner method.
     */
    public function testGetWinner()
    {
        $logic = new GameLogic();

        // spelare vinner
        $card1 = new CardGraphic("K", "spades");
        $card2 = new CardGraphic("Q", "hearts");
        $logic->getPlayer()->getHand()->addCard($card1);
        $logic->getBank()->getHand()->addCard($card2);

        $winner = $logic->getWinner();
        $exp = "Spelare";
        $this->assertEquals($exp, $winner);

        // bank vinner
        $card3 = new CardGraphic("3", "spades");
        $logic->getBank()->getHand()->addCard($card3);

        $winner = $logic->getWinner();
        $exp = "Bank";
        $this->assertEquals($exp, $winner);

        // bank går över gräns
        $card4 = new CardGraphic("7", "spades");
        $logic->getBank()->getHand()->addCard($card4);

        $winner = $logic->getWinner();
        $exp = "Spelare";
        $this->assertEquals($exp, $winner);
    }

    /**
     * Test game over methods.
     */
    public function testGameOver()
    {
        $logic = new GameLogic();
        $logic->setGameOver();

        $this->assertTrue($logic->isGameOver());
    }
}