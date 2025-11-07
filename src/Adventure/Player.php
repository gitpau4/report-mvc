<?php

namespace App\Adventure;

/**
 * Represents a player.
 *
 * @autor Paula Frölander, pafo24
 */
class Player
{
    private array $inventory = [];

    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function addItem(Item $item): void
    {
        $this->inventory[] = $item;
    }

    public function removeItem(string $itemName): void
    {
        foreach ($this->inventory as $key => $item) {
            if ($item->getName() === $itemName) {
                array_splice($this->inventory, $key, 1);
                break;
            }
        }
    }

    public function hasItem(string $itemName): bool
    {
        foreach ($this->inventory as $item) {
            if ($item->getName() === $itemName) {
                return true;
            }
        }

        return false;
    }
}
