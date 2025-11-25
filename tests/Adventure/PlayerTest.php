<?php

namespace App\Adventure;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    public function testAddAndRemoveItem(): void
    {
        $player = new Player();
        $item = new Item("item", "an item", true);

        // add
        $player->addItem($item);

        $this->assertEquals([$item], $player->getInventory());

        // remove
        $player->removeItem("item");

        $this->assertEquals([], $player->getInventory());
    }

    public function testHasItem(): void
    {
        $player = new Player();
        $item = new Item("item", "an item", true);
        $player->addItem($item);

        $this->assertTrue($player->hasItem("item"));
        $this->assertFalse($player->hasItem("anotherItem"));
    }
}
