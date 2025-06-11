<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify it is a CardHand object.
     */
    public function testCreateObject()
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);
    }

    /**
     * Verify that class can add card to hand and return number of cards in hand.
     */
    public function testAddCard()
    {
        $cardHand = new CardHand();
        $card = new CardGraphic("9", "clubs");

        $cardHand->addCard($card);
        $res = $cardHand->getHand();
        $exp = [$card];
        $this->assertEquals($exp, $res);

        $expNumCards = 1;
        $resNumCards = $cardHand->getNumberOfCards();
        $this->assertEquals($expNumCards, $resNumCards);
    }

    /**
     * Test getValues and getSuits methods.
     */
    public function testGetSuitsAndValues()
    {
        $cardHand = new CardHand();
        $card1 = new CardGraphic("9", "clubs");
        $card2 = new CardGraphic("K", "diamonds");

        $cardHand->addCard($card1);
        $cardHand->addCard($card2);

        $resValues = $cardHand->getValues();
        $expValues = ["9", "K"];
        $this->assertEquals($expValues, $resValues);

        $resSuits = $cardHand->getSuits();
        $expSuits = ["clubs", "diamonds"];
        $this->assertEquals($expSuits, $resSuits);
    }

    /**
     * Test getPoints method.
     */
    public function testGetPoints()
    {
        $cardHand = new CardHand();
        $card1 = new CardGraphic("9", "clubs");
        $card2 = new CardGraphic("A", "diamonds");
        $card3 = new CardGraphic("J", "hearts");
        $card4 = new CardGraphic("Q", "hearts");
        $card5 = new CardGraphic("K", "diamonds");

        $cardHand->addCard($card1);
        $cardHand->addCard($card2);
        $cardHand->addCard($card3);
        $cardHand->addCard($card4);
        $cardHand->addCard($card5);

        $res = $cardHand->getPoints();
        $exp = 46;
        $this->assertEquals($exp, $res);
    }
}