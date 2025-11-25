<?php

namespace App\Adventure;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Room.
 */
class RoomTest extends TestCase
{
    public function testCreateObject(): void
    {
        $exits = [
            "west" => null,
            "east" => 2,
            "south" => null,
            "north" => 3
        ];

        $items = [
            [
                "name" => "shoes",
                "description" => "some shoes",
                "canTake" => true
            ]
        ];

        $action = [
            "name" => "jump",
            "requires" => null,
            "gives" => null,
            "failMessage" => "fail",
            "successMessage" => "success",
            "endOnFail" => false,
            "endOnSuccess" => true
        ];

        $room = new Room(6, "room 6", "room6.jpg", $exits, $items, $action);
        $this->assertInstanceOf("\App\Adventure\Room", $room);

        $this->assertEquals(6, $room->getRoomId());
        $this->assertEquals("room 6", $room->getDescription());
        $this->assertEquals("room6.jpg", $room->getImg());
        $this->assertEquals($exits, $room->getExits());
        $this->assertEquals($items, $room->getItems());
        $this->assertEquals($action, $room->getAction());
    }

    public function testRemoveItem(): void
    {
        $item1 = new Item("item1", "item 1", true);
        $item2 = new Item("item2", "item 2", true);

        $room = new Room(7, "room 7", "room7.jpg", [], [$item1, $item2], null);

        $room->removeItem("item1");

        $this->assertEquals([$item2], $room->getItems());
    }

    public function testActionDone(): void
    {
        $action = [
            "name" => "jump",
            "requires" => null,
            "gives" => null,
            "failMessage" => "fail",
            "successMessage" => "success",
            "endOnFail" => false,
            "endOnSuccess" => true
        ];

        $room = new Room(8, "room 8", "room8.jpg", [], [], $action);

        $room->actionDone();

        $this->assertNull($room->getAction());
    }
}
