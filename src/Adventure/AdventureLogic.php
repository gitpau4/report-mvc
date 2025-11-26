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

    public function __construct(string $jsonPath)
    {
        $this->initRooms($jsonPath);
        $this->player = new Player();
        $this->currentRoom = $this->rooms[1];
        $this->isGameOver = false;
    }

    /**
     * Create rooms from json data file.
     */
    public function initRooms(string $jsonPath): void
    {
        // $path = __DIR__ . '/data/rooms.json';
        $jsonString = file_get_contents($jsonPath);
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

    public function getPlayer(): Player
    {
        return $this->player;
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

    /**
     * Move in given direction to a new room, if possible.
     */
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

    /**
     * Pick up item and add to player inventory.
     */
    public function pickItem(string $itemName): string
    {
        $items = $this->currentRoom->getItems();

        // om rummet har item, lägg i spelares inventory
        if (!empty($items) && isset($items[$itemName])) {
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

    /**
     * Interact with object in room.
     */
    public function interact(): string
    {
        $action = $this->currentRoom->getAction();
        $items = $this->currentRoom->getItems();

        if ($action === null) {
            return "Nothing to interact with.";
        }

        // om spelaren inte har vad som krävs för att lyckas med interaktionen
        if ($action["requires"] && !$this->player->hasItem($action["requires"])) {
            $this->isGameOver = $action["endOnFail"];
            return $action["failMessage"];
        }

        // om man får något av interaktionen
        if ($action["gives"]) {
            $items[$action["gives"]]->makeItemTakeable();
        }

        $this->player->removeItem($action["requires"]);
        $this->currentRoom->actionDone();
        $this->isGameOver = $action["endOnSuccess"];
        return $action["successMessage"];
    }
}
