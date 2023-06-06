<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\ClubDto;
use App\Service\ClubManager;
use App\Form\Model\PlayerDto;
use App\Form\Type\ClubFormType;
use App\Service\PlayerManager;
use App\Service\CoachManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

class ClubFormProcessor
{
    private $clubManager;
    private $playerManager;
    private $coachManager;
    private $formFactory;

    public function __construct(
        ClubManager $clubManager,
        PlayerManager $playerManager,
        CoachManager $coachManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->clubManager = $clubManager;
        $this->playerManager = $playerManager;
        $this->coachManager = $coachManager;
        $this->formFactory = $formFactory;
        }

    public function __invoke(Club $club, Request $request): array
    {
        $clubDto = ClubDto::createFromClub($club);

        $originalPlayers = new ArrayCollection();
        foreach($club->getPlayers() as $player) {
            $playerDto = PlayerDto::createFromPlayer($player);
            $clubDto->players[] = $playerDto;
            $originalPlayers->add($playerDto);
        }

        $form = $this->formFactory->create(ClubFormType::class, $clubDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not valid'];
        }
        if($form->isValid() )
        {
            // Validating registered players
            $registeredPlayers = $this->isPlayerRegistered($clubDto, $club);
            if ($registeredPlayers['result']) {
                return [null, $registeredPlayers['message']];
            }

            // Validating budget
            if ($this->isOverBudget($clubDto)) {
                return [null, 'The budget has been exceeded'];
            }
            // Reemove players
            foreach($originalPlayers as $originalPlayerDto){
                if(!in_array($originalPlayerDto, $clubDto->players)){
                    $player = $this->playerManager->find($originalPlayerDto->id);
                    $club->removePlayer($player);
                }
            }
            // Add players
            foreach($clubDto->players as $newPlayerDto ){
                if(!$originalPlayers->contains($newPlayerDto)) {
                    $player = $this->playerManager->find($newPlayerDto->id ?? 0);
                    if(!$player){
                        $player = $this->playerManager->create();
                        $player->setName($newPlayerDto->name);
                        $player->setSalary($newPlayerDto->salary);
                        $this->playerManager->persist($player);
                    }
                    $club->addPlayer($player);
                }
            }
            $club->setName($clubDto->name);
            $club->setBudget($clubDto->budget);
            // It's missing to add or remove a coach according to the data
            $club->setCoach($clubDto->coach);
            $this->clubManager->save($club);
            $this->clubManager->reload($club);
            return [$club, null];
        }
        return [null, $form] ;
    }

    private function isOverBudget(clubDto $clubDto)
    {
        $totalSalaries = 0;
        $coachSalary = 0;
        $coachId = $clubDto->coach;
        if ($coachId){
            $coach = $this->coachManager->find($coachId);
            $coachSalary = $coach->getSalary();
        }
        $totalSalaries += $coachSalary;
        $budget = $clubDto->budget;
        foreach($clubDto->players as $onePlayer)
        {
            if ($onePlayer->id){
                $playerSalary = $this->playerManager->find($onePlayer->id)->getSalary();
            } else{
                $playerSalary = ($onePlayer->salary>0) ? $onePlayer->salary : 0;
            }
            $totalSalaries += $playerSalary;
        }

        if (($budget - $totalSalaries)>=0){
            return 0;
        } else{
            return 1;
        }
    }

    private function isPlayerRegistered(clubDto $clubDto, Club $club)
    {
        $clubId = $club->getId();
        $finalText = '';
        $result = 0;
        foreach($clubDto->players as $onePlayer)
        {
            if ($onePlayer->id){
                $playerClub = $this->playerManager->find($onePlayer->id)->getClub();
                if (!empty($playerClub)){
                    if((!empty($playerClub->getId())) && ($clubId !=$playerClub->getId())  ){
                        $playerClub = ($this->playerManager->find($onePlayer->id))->getClub();
                        $playerName = ($this->playerManager->find($onePlayer->id))->getName();
                        $finalText .= 'Player id: ' . $onePlayer->id ." - " . $playerName . " - : " . $playerClub->getName() .' ** ' ;
                    }
                }
            }
        }
        if ($finalText != '' ) $result = 1;
        $playersRegistered = array(
                                'result' => $result,
                                'message' => $finalText
                            );
        return $playersRegistered;
    }

    private function isCoachRegistered(clubDto $clubDto)
    {

    }
}
