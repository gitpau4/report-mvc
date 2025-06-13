<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object and verify it is a CardGraphic object.
     */
    public function testCreateObject(): void
    {
        $cardGraphic = new CardGraphic("5", "hearts");
        $this->assertInstanceOf("\App\Card\CardGraphic", $cardGraphic);

        $res = $cardGraphic->getAsString();
        $exp = "5 ♥";
        $this->assertEquals($exp, $res);
    }
}
