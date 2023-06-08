<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 *   The club is the organization that play football
 *   and contract players and a coach to participate
 *   in a footballleague
 *   It needs a budget to support the contracts it has
 *   with the players and the coach
 *
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    /**
     *
     *   It's the identifier of the club
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *  The name of the club
     *
     * @ORM\Column(type="string", length=100)
     */
    private ?string $name = null;

    /**
     *  The club's budget to pay the coach and the players
     *
     * @ORM\Column(type="integer")
     */
    private ?int $budget = null;

    /**
     *
     *  The collection of players the club has hired
     *
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="club")
     */
    private \Doctrine\Common\Collections\Collection $players;

    /**
     *  The coach that the club has hired to direct the players
     *
     * @ORM\OneToOne(targetEntity=Coach::class, mappedBy="club", cascade={"persist", "remove"})
     */
    private ?\App\Entity\Coach $coach = null;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

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

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     *
     *  This method allows to get all the payroll the club has:
     *  - The players and the coach
     *
     *  It's used to calculate if the budget has been exceeded
     *  - Creating or modifying a club
     *  - Creating or modifying a player
     *  - Creating or modifying a coach
     */
    public function getTotalSalaries(): int
    {
        $allPlayers = $this->getPlayers();
        $totalSalaries = 0;
        foreach($allPlayers as $onePlayer)
        {
            $totalSalaries += $onePlayer->getSalary();
        }
        return $totalSalaries;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    /**
     * Add a new player to the club
     *
     * @return self
     */
    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setClub($this);
        }

        return $this;
    }

    /**
     * Remove a player from the club
     *
     * @return self
     */
    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getClub() === $this) {
                $player->setClub(null);
            }
        }

        return $this;
    }

    /**
     * Get the coach from the club
     *
     * @return Coach|null
     */
    public function getCoach(): ?Coach
    {
        return $this->coach;
    }

    /**
     * Assign a coach to the club
     *
     * @return self
     */
    public function setCoach(?Coach $coach): self
    {
        // unset the owning side of the relation if necessary
        if ($coach === null && $this->coach !== null) {
            $this->coach->setClub(null);
        }

        // set the owning side of the relation if necessary
        if ($coach !== null && $coach->getClub() !== $this) {
            $coach->setClub($this);
        }

        $this->coach = $coach;

        return $this;
    }
}
