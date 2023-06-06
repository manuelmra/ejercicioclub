<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayerManager
{
    private $em;
    private $playerRepository;

    public function __construct(EntityManagerInterface $em, PlayerRepository $playerRepository)
    {
        $this->em = $em;
        $this->playerRepository = $playerRepository;
    }

    public function find(int $id): ?Player
    {
        return $this->playerRepository->find($id);
    }

    public function getRepository(): PlayerRepository
    {
        return $this->playerRepository;
    }

    public function create(): Player
    {
        $player = new Player();
        return $player;
    }

    public function persist(Player $player): Player
    {
        $this->em->persist($player);
        return $player;
    }

    public function save(Player $player): Player
    {
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    public function reload(Player $player): Player
    {
        $this->em->refresh($player);
        return $player;
    }
    
    public function delete(Player $player){
        $this->em->remove($player);
        $this->em->flush();
    }
}
