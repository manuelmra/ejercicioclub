<?php

namespace App\Service;

use App\Entity\Club;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClubManager
{
    private $em;
    private $clubRepository;

    public function __construct(EntityManagerInterface $em, ClubRepository $clubRepository)
    {
        $this->em = $em;
        $this->clubRepository = $clubRepository;
    }

    public function find(int $id): ?Club
    {
        return $this->clubRepository->find($id);
    }

    public function getRepository(): ClubRepository
    {
        return $this->clubRepository;
    }

    public function create(): Club
    {
        $club = new Club();
        return $club;
    }

    public function save(Club $club): Club
    {
        $this->em->persist($club);
        $this->em->flush();
        return $club;
    }

    public function reload(Club $club): Club
    {
        $this->em->refresh($club);
        return $club;
    }
    
    public function delete(Club $club){
        $this->em->remove($club);
        $this->em->flush();
    }
}
