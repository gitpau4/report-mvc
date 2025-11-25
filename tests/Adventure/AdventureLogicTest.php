<?php

namespace App\Adventure;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class AdventureLogic.
 */
class AdventureLogicTest extends TestCase
{
    public function testMove(): void
    {
        $logic = new AdventureLogic(__DIR__ . '/../../data/adventure_test_rooms.json');

        $this->assertEquals(1, $logic->getCurrentRoom()->getRoomId());

        // valid direction (room 1 -> room 2)
        $moveRes = $logic->move("north");

        $this->assertEquals("You go north.", $moveRes);

        $this->assertEquals(2, $logic->getCurrentRoom()->getRoomId());

        // unvalid direction
        $moveRes2 = $logic->move("east");

        $this->assertEquals("You can't go that way.", $moveRes2);
    }

    public function testPickItem(): void
    {
        $logic = new AdventureLogic(__DIR__ . '/../../data/adventure_test_rooms.json');

        $pickRes1 = $logic->pickItem("item");

        $this->assertEquals("You pick up the item.", $pickRes1);

        $logic->move("north");

        $pickRes2 = $logic->pickItem("thing");

        $this->assertEquals("You can't reach the thing.", $pickRes2);

        $logic->move("north");
        $pickRes3 = $logic->pickItem("item2");

        $this->assertEquals("Nothing to pick.", $pickRes3);
    }

    public function testInteract(): void
    {
        $logic = new AdventureLogic(__DIR__ . '/../../data/adventure_test_rooms.json');
        $logic->move("north");

        // har inte item
        $actionRes1 = $logic->interact();

        $this->assertEquals("fail", $actionRes1);

        // har item
        $logic->move("south");
        $logic->pickItem("item");
        $logic->move("north");

        $actionRes2 = $logic->interact();

        $this->assertEquals("success", $actionRes2);
    }

    public function testIsGameOver(): void
    {
        $logic = new AdventureLogic(__DIR__ . '/../../data/adventure_test_rooms.json');

        $this->assertFalse($logic->getIsGameOver());
    }
}
