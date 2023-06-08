<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 *   The coach is the one who directs the players in the club.
 *
 * @ORM\Entity(repositoryClass=CoachRepository::class)
 */
class Coach
{
    /**
     *
     *   It's the identifier of the coach
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     *  The name of the coach
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     *
     *  It's the salary of the coach that directs the players
     *
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     *
     * It's the club the coach directs
     *
     * @ORM\OneToOne(targetEntity=Club::class, inversedBy="coach", cascade={"persist", "remove"})
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
