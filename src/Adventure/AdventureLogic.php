<?php

namespace App\Adventure;

use App\Adventure\Room;
use App\Adventure\Item;
use App\Adventure\Player;

/**
 * Handles logic for adventure game.
 *
 * @autor Paula Frölander, pafo24
 */
class AdventureLogic
{
    private array $rooms = [];
    private Player $player;
    private Room $currentRoom;
    private bool $isGameOver;

    public function __construct()
    {
        $this->initRooms();
        $this->player = new Player();
        $this->currentRoom = $this->rooms[1];
        $this->isGameOver = false;
    }

    public function initRooms(): void
    {
        $path = __DIR__ . '/data/rooms.json';
        $jsonString = file_get_contents($path);
        $roomsData = json_decode($jsonString, true);

        foreach ($roomsData as $roomData) {
            $items = [];
            foreach ($roomData["items"] as $item) {
                $items[$item["name"]] = new Item($item["name"], $item["description"], $item["canTake"]);
            }

            $roomId = $roomData["id"];
            $description = $roomData["description"];
            $img = $roomData["img"];
            $exits = $roomData["exits"];
            $action = $roomData["action"];

            $this->rooms[$roomId] = new Room($roomId, $description, $img, $exits, $items, $action);
        }
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function getCurrentRoom(): Room
    {
        return $this->currentRoom;
    }

    public function getIsGameOver(): bool
    {
        return $this->isGameOver;
    }

    public function getInventoryAsString(): string
    {
        $inventory = $this->player->getInventory();

        if (empty($inventory)) {
            return "Your inventory is empty.";
        }

        $itemNames = [];
        foreach ($inventory as $item) {
            $itemNames[] = $item->getName();
        }

        return "You are carrying: " . implode(", ", $itemNames);
    }

    public function move(string $direction): string
    {
        $exits = $this->currentRoom->getExits();

        if (isset($exits[$direction])) {
            $nextRoomId = $exits[$direction];
            $this->currentRoom = $this->rooms[$nextRoomId];

            return "You go " . $direction . ".";
        }
        
        return "You can't go that way.";
    }

    public function pickItem(string $itemName): string
    {
        $items = $this->currentRoom->getItems();

        // om rummet har item, lägg i spelares inventory
        if (!empty($items)) {
            if ($items[$itemName]->canTakeItem()) {
                $this->player->addItem($items[$itemName]);

                // ta bort föremålet från rummet
                $this->currentRoom->removeItem($itemName);

                return "You pick up the " . $itemName . ".";
            }
            
            return "You can't reach the " . $itemName . ".";
        }
        
        return "Nothing to pick.";
    }

    public function interact(string $action): string
    {
        $action = $this->currentRoom->getAction();
        $items = $this->currentRoom->getItems();

        if ($action === "armor") {
            // om spelare har amulet, då gå armor sönder och canTake key blir true
            if ($this->player->hasItem("amulet")) {
                $items["key"]->makeItemTakeable();
                $this->currentRoom->actionDone();

                return "Your magic amulet starts to glow brighter and the cursed armor falls to the ground. 
                        The key is now within reach.";
            }

            $this->isGameOver = true;
            return "The cursed armors eyeholes light up with an eerie blue glow. 
                    You don't have any protection, the armor strikes you down. 
                    Game Over!";
        }

        if ($action === "treasure") {
            if ($this->player->hasItem("key")) {
                $this->player->removeItem("key");
                $this->currentRoom->actionDone();

                $this->isGameOver = true;
                return "The key fits perfectly. 
                        The chest opens with a creak and inside you find piles of gold.
                        You Win!";
            }

            return "The treasure chest is locked.";
        }
    }
}
