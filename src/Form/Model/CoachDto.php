<?php

namespace App\Form\Model;

use App\Entity\Coach;

class CoachDto{
    public $id;
    public $name;
    public $salary;
    public $club;

    public static function createFromCoach(Coach $coach): self
    {
        $dto = new self();
        $dto->id = $coach->getId();
        $dto->name = $coach->getName();
        $dto->salary = $coach->getSalary();
        $dto->club = $coach->getClub();
        return $dto;
    }
}