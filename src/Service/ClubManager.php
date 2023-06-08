<?php

namespace App\Service;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 *  The manager for the club allows to work independent from other models
 * - In this case handle all basic operations with the repository
 *
 */

class ClubManager
{
    public function __construct(
        /**
         * It'sthe club entity manager
         */
        private readonly EntityManagerInterface $em,
        /**
         * It's the club repository
         */
        private readonly ClubRepository $clubRepository
    )
    {
    }

    /**
     * Allows to find a club in the repository
     *
     * @param integer $id
     * @return Club|null
     */
    public function find(int $id): ?Club
    {
        return $this->clubRepository->find($id);
    }

    /**
     * Get the club repository
     *
     * @return ClubRepository
     */
    public function getRepository(): ClubRepository
    {
        return $this->clubRepository;
    }

    /**
     * Creates a new club
     *
     * @return Club
     */
    public function create(): Club
    {
        $club = new Club();
        return $club;
    }

    /**
     * Allows to save temporary the data before saving definitely
     *
     * @param Club $club
     * @return Club
     */
    public function persist(Club $club): Club
    {
        $this->em->persist($club);
        return $club;
    }

    /**
     * It saves the data definitely
     *
     * @param Club $club
     * @return Club
     */
    public function save(Club $club): Club
    {
        $this->em->persist($club);
        $this->em->flush();
        return $club;
    }

    /**
     *  Refresh the data lately saved
     *
     * @param Club $club
     * @return Club
     */
    public function reload(Club $club): Club
    {
        $this->em->refresh($club);
        return $club;
    }

    /**
     * Remove the record from the database
     *
     * @param Club $club
     * @return void
     */
    public function delete(Club $club){
        $this->em->remove($club);
        $this->em->flush();
    }
}
