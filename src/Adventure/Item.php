<?php

namespace App\Adventure;

/**
 * Represents an item.
 *
 * @autor Paula Frölander, pafo24
 */
class Item
{
    private string $name;
    private string $description;
    private bool $canTake;

    public function __construct(string $name, string $description, bool $canTake)
    {
        $this->name = $name;
        $this->description = $description;
        $this->canTake = $canTake;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function canTakeItem(): bool
    {
        return $this->canTake;
    }

    public function makeItemTakeable(): void
    {
        $this->canTake = true;
    }
}
