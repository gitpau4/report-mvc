<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify it is a Player object.
     */
    public function testCreateObject(): void
    {
        $player = new Player("name");
        $this->assertInstanceOf("\App\Card\Player", $player);

        $name = $player->getName();
        $this->assertEquals("name", $name);
    }

    /**
     * Test getPoints method.
     */
    public function testPlayerHand(): void
    {
        $player = new Player("name");
        $hand = $player->getHand();
        $this->assertInstanceOf(CardHand::class, $hand);

        $card = new CardGraphic("9", "clubs");
        $hand->addCard($card);
        $points = $player->getPoints();
        $this->assertEquals(9, $points);
    }
}
