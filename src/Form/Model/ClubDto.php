<?php

namespace App\Form\Model;

use App\Entity\Club;

class ClubDto{
    public $name;
    public $budget;
    public $coach;
    public $players;

    public function __construct()
    {
        $this->players = [];
    }

    public static function createFromClub(Club $club): self
    {
        $dto = new self();
        $dto->name = $club->getName();
        $dto->budget = $club->getBudget();
        $dto->coach = $club->getCoach();
        return $dto;
    }
}