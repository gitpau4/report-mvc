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
     * Verify addCard method.
     */
    public function testAddCard()
    {
        $cardHand = new CardHand();
        $card = new CardGraphic("9", "clubs");

        $cardHand->addCard($card);
        $res = $cardHand->getHand();
        $exp = [$card];
        $this->assertEquals($exp, $res);
    }
}