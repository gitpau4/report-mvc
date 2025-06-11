<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object and verify it is a DeckOfCards object.
     */
    public function testCreateObject()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deck);
    }

    /**
     * Test get deck methods.
     */
    public function testgetDeck()
    {
        $deck = new DeckOfCards();
        
        $res = $deck->getDeck();
        $this->assertCount(52, $res);
        $this->assertIsArray($res);

        $original = $deck->getOriginalDeck();
        $this->assertEquals($res, $original);
    }

    /**
     * Test that shuffle method changes the deck and reset method changes it to original.
     */
    public function testShuffle()
    {
        $deck = new DeckOfCards();
        $before = $deck->getDeck();
        $deck->shuffle();
        $after = $deck->getDeck();

        $this->assertNotEquals($before, $after);

        // reset
        $deck->reset();
        $resetedDeck = $deck->getDeck();
        $original = $deck->getOriginalDeck();

        $this->assertEquals($resetedDeck, $original);
    }

    /**
     * Test draw one or more cards.
     */
    public function testdrawCards()
    {
        $deck = new DeckOfCards();
        $drawnCard = $deck->drawCard();
        $this->assertCount(51, $deck->getDeck());
        $this->assertEquals(new CardGraphic("A", "diamonds"), $drawnCard);

        $drawnCards = $deck->drawNumberCards(3);
        $this->assertCount(48, $deck->getDeck());
        $this->assertIsArray($drawnCards);
    }
}