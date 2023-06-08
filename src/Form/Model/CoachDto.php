<?php

namespace App\Form\Model;

use App\Entity\Coach;

class CoachDto{

    public $id;

    /**
     * The name of the coach
     *
     * @var string
     */
    public $name;

    /**
     * The salary of the coach
     *
     * @var int
     */
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