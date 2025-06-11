<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify it is a Card object.
     */
    public function testCreateObject()
    {
        $card = new Card("2", "spades");
        $this->assertInstanceOf("\App\Card\Card", $card);

        $resValue = $card->getValue();
        $expValue = "2";
        $this->assertEquals($expValue, $resValue);

        $resSuit = $card->getSuit();
        $expSuit = "spades";
        $this->assertEquals($expSuit, $resSuit);
    }
}