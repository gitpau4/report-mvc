<?php

namespace App\Adventure;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Item.
 */
class ItemTest extends TestCase
{
    public function testCreateObject(): void
    {
        $item = new Item("coin", "a coin", true);
        $this->assertInstanceOf("\App\Adventure\Item", $item);

        $this->assertEquals("coin", $item->getName());
        $this->assertEquals("a coin", $item->getDescription());
        $this->assertEquals(true, $item->canTakeItem());
    }

    public function testMakeItemTakeable(): void
    {
        $item = new Item("bottle", "a bottle", false);

        $item->makeItemTakeable();

        $this->assertTrue($item->canTakeItem());
    }
}
