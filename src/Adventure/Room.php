<?php

namespace App\Adventure;

/**
 * Represents a room in the dungeon.
 *
 * @autor Paula Frölander, pafo24
 */
class Room
{
    private int $roomId;
    private string $description;
    private array $exits;
    private string $img;
    private array $items;
    private ?array $action;     // rustning och kista tex

    public function __construct(int $roomId, string $description, string $img, array $exits = [], array $items = [], ?array $action = null)
    {
        $this->roomId = $roomId;
        $this->description = $description;
        $this->img = $img;
        $this->exits = $exits;
        $this->items = $items;
        $this->action = $action;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImg(): string
    {
        return $this->img;
    }

    public function getExits(): array
    {
        return $this->exits;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getAction(): ?array
    {
        return $this->action;
    }

    /**
     * Remove item from items list.
     */
    public function removeItem(string $itemName): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getName() === $itemName) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                break;
            }
        }
    }

    /**
     * Make action null.
     */
    public function actionDone(): void
    {
        $this->action = null;
    }
}
