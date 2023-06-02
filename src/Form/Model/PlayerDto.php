<?php

namespace App\Form\Model;

use App\Entity\Player;

class PlayerDto{
    public $id;
    public $name;
    public $salary;
    public $club;

    public static function createFromPlayer(Player $player): self
    {
        $dto = new self();
        $dto->id = $player->getId();
        $dto->name = $player->getName();
        $dto->salary = $player->getSalary();
        $dto->club = $player->getClub();
        return $dto;
    }
}