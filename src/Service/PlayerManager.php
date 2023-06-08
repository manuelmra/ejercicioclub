<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 *  The manager for the club allows to work independent from other models
 * - In this case handle all basic operations with the repository
 *
 */
class PlayerManager
{
    public function __construct(
        /**
         * It's the player entity manager
         */
        private readonly EntityManagerInterface $em,
        /**
         * It's the club repository
         */
        private readonly PlayerRepository $playerRepository
    )
    {
    }

    /**
     * Allows to find a coach in the repository
     *
     * @return Player|null
     */
    public function find(int $id): ?Player
    {
        return $this->playerRepository->find($id);
    }

    /**
     * Get the player repository
     *
     * @return PlayerRepository
     */
    public function getRepository(): PlayerRepository
    {
        return $this->playerRepository;
    }

    /**
     * Creates a new player
     *
     * @return Player
     */
    public function create(): Player
    {
        $player = new Player();
        return $player;
    }

    /**
     * Allows to save temporary the data before saving definitely
     *
     * @return Player
     */
    public function persist(Player $player): Player
    {
        $this->em->persist($player);
        return $player;
    }

    /**
     * It saves the data definitely
     *
     * @return Player
     */
    public function save(Player $player): Player
    {
        $this->em->persist($player);
        $this->em->flush();
        return $player;
    }

    /**
     *  Refresh the data lately saved
     *
     * @return Player
     */
    public function reload(Player $player): Player
    {
        $this->em->refresh($player);
        return $player;
    }
    
    /**
     * Remove the record from the database
     *
     * @return void
     */
    public function delete(Player $player){
        $this->em->remove($player);
        $this->em->flush();
    }
}
