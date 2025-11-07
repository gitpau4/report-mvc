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
class GameLogic
{
    private array $rooms = [];
    private Player $player;
    // ska denna va id eller bättre med Room objekt ??????????????????????
    // private int $currentRoomId;
    private int $currentRoom;

    public function __construct()
    {
        $this->initRooms();
        $this->player = new Player();
        $this->currentRoom = $this->rooms[1];
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
            $exits = $roomData["exits"];
            $action = $roomData["action"];

            $this->rooms[$roomId] = new Room($roomId, $description, $exits, $items, $action);
        }
    }

    // bör denna va private istället ????????????????
    // public function getCurrentRoom(): Room
    // {
    //     return $this->rooms[$this->currentRoomId];
    // }

    public function move(string $direction): void
    {
        // $currentRoom = $this->getCurrentRoom();
        $exits = $this->currentRoom->getExits();

        if (isset($exits[$direction])) {
            $nextRoomId = $exits[$direction];
            $this->currentRoom = $this->rooms[$nextRoomId];
        }
        // else man kan inte gå hit
    }

    public function pickItem(string $itemName): void
    {
        // $currentRoom = $this->getCurrentRoom();
        $items = $this->currentRoom->getItems();

        // om rummet har item, lägg i spelares inventory
        if (!empty($items)) {
            if ($items[$itemName]->canTakeItem()) {
                $this->player->addItem($items[$itemName]);

                // ta bort föremålet från rummet
                $this->currentRoom->removeItem($itemName);
            }
            // else man får inte plocka
        }
        // else rummet har inget att plocka
    }

    public function interact(string $action): string
    {
        $action = $this->currentRoom->getAction();
        $items = $this->currentRoom->getItems();

        if ($action === "armor") {
            // om spelare har amulet, då gå armor sönder och canTake key blir true
            if ($this->player->hasItem("amulet")) {
                $items["key"]->makeItemTakeable();

                $this->player->removeItem("amulet");

                return "The armor falls to the ground, clearing the path to the key.";
            }

            // här kan det istället vara game over om man rör rustningen utan amulett
            return "The armor guards the key.";
        }

        if ($action === "treasure") {
            if ($this->player->hasItem("key")) {
                $this->player->removeItem("key");
                return "You open the treasure chest with the key and find lots of money!! You win!";
            }

            return "The treasure chest is locked.";
        }
    }

    // move
    // useItem eller interact
    // pickItem, addar till player samt tar bort från rum
    // game over
    // se sitt inventory
}
