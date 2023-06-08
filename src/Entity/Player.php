<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 *   The player plays for the club is registered
 *
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     *
     *   It's the identifier of the player
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     *  The name of the player
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     *
     *  It's the salary of the player that plays in a club
     *
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     *
     * It's the club the player plays
     *
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="players")
     */
    private $club;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }
}
