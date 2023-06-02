<?php

namespace App\Service;

use App\Entity\Coach;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;

class CoachManager
{
    private $em;
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

    public function getRepository(): CoachRepository
    {
        return $this->coachRepository;
    }

    public function create(): Coach
    {
        $coach = new Coach();
        return $coach;
    }

    public function save(Coach $coach): Coach
    {
        $this->em->persist($coach);
        $this->em->flush();
        return $coach;
    }

    public function reload(Coach $coach): Coach
    {
        $this->em->refresh($coach);
        return $coach;
    }
    
    public function delete(Coach $coach){
        $this->em->remove($coach);
        $this->em->flush();
    }
}
