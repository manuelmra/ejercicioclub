<?php

namespace App\Service;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 *  The manager for the club allows to work independent from other models
 * - In this case handle all basic operations with the repository
 *
 */
class CoachManager
{
    /**
     * It's the coach entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * It's the coach repository
     *
     * @var ClubRepository
     */
    private $coachRepository;

    public function __construct(EntityManagerInterface $em, CoachRepository $coachRepository)
    {
        $this->em = $em;
        $this->coachRepository = $coachRepository;
    }

    public function find(int $id): ?Coach
    {
        return $this->coachRepository->find($id);
    }

    /**
     * Get the coach repository
     *
     * @return CoachRepository
     */
    public function getRepository(): CoachRepository
    {
        return $this->coachRepository;
    }

    /**
     * Creates a new coach
     *
     * @return Coach
     */
    public function create(): Coach
    {
        $coach = new Coach();
        return $coach;
    }

    /**
     * Allows to save temporary the data before saving definitely
     *
     * @param Coach $coach
     * @return Coach
     */
    public function persist(Coach $coach): Coach
    {
        $this->em->persist($coach);
        return $coach;
    }

    /**
     * It saves the data definitely
     *
     * @param Coach $coach
     * @return Coach
     */
    public function save(Coach $coach): Coach
    {
        $this->em->persist($coach);
        $this->em->flush();
        return $coach;
    }

    /**
     *  Refresh the data lately saved
     *
     * @param Coach $coach
     * @return Coach
     */
    public function reload(Coach $coach): Coach
    {
        $this->em->refresh($coach);
        return $coach;
    }
    
    /**
     * Remove the record from the database
     *
     * @param Coach $coach
     * @return void
     */
    public function delete(Coach $coach){
        $this->em->remove($coach);
        $this->em->flush();
    }
}
