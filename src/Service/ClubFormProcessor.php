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

/**
 *
 * Processes the data club from the form
 * and if it successes save the data
 *
 */
class ClubFormProcessor
{
    /**
     * The manager of the club
     *
     * @var ClubManager
     */
    private $clubManager;

    /**
     * The manager of the player
     *
     * @var PlayerManager
     */
    private $playerManager;

    /**
     * The manager of the CoaCH
     *
     * @var CoachManager
     */
    private $coachManager;

    /**
     * It¿s the forma factory
     *
     * @var FormFactoryInterface
     */
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
            // Validating registered coach
            $registeredPlayers = $this->isCoachRegistered($clubDto, $club);
            if ($registeredPlayers) {
                return [null, 'The coach is already registered in another club.'];
            }

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
            // $club->setCoach($clubDto->coach);
            $this->clubManager->save($club);
            $this->clubManager->reload($club);
            return [$club, null];
        }
        return [null, $form] ;
    }

    /**-
     *  Validate if the payroll exceeds the budget
     *
     * @param clubDto $clubDto
     *
     * @return int
     * 1: exceeds budget  0: under budget
     */
    private function isOverBudget(clubDto $clubDto)
    {
        $payroll = 0;
        // Find the current coach salary from the repository
        $coachSalary = 0;
        $coachId = $clubDto->coach;
        if ($coachId){
            $coach = $this->coachManager->find($coachId);
            $coachSalary = $coach->getSalary();
        }
        // Add coach salary to the payroll
        $payroll += $coachSalary;
        $budget = $clubDto->budget;
        // Add each player salary to the payroll
        foreach($clubDto->players as $onePlayer)
        {
            if ($onePlayer->id){
                //Add the player salary if the player is already saved
                $playerSalary = $this->playerManager->find($onePlayer->id)->getSalary();
            } else{
                //Add the salary from the current data if the player is new
                $playerSalary = ($onePlayer->salary>0) ? $onePlayer->salary : 0;
            }
            $payroll += $playerSalary;
        }

        if (($budget - $payroll)>=0){
            return 0;
        } else{
            return 1;
        }
    }

    /**-
     *  Validate if any player of the current data is registered in the club
     *
     * @param clubDto $clubDto
     * @param Club    $club
     *
     * @return int
     * 1: registered  0: not registered
     */
    private function isPlayerRegistered(clubDto $clubDto, Club $club)
    {
        $clubId = $club->getId();
        $finalText = '';
        $result = 0;
        foreach($clubDto->players as $onePlayer)
        {
            if ($onePlayer->id){
                // Find out if the player is in the DB
                $playerClub = $this->playerManager->find($onePlayer->id)->getClub();
                // Only the players that are in the DB are processed
                if (!empty($playerClub)){
                    // Evaluate if the player is not registered in a club
                    // And if it's in registered in the current club
                    if((!empty($playerClub->getId())) && ($clubId !=$playerClub->getId())  ){
                        $playerClub = ($this->playerManager->find($onePlayer->id))->getClub();
                        $playerName = ($this->playerManager->find($onePlayer->id))->getName();
                        // Add the data of each player that are already resgistered in other club
                        // To show to the user amd he can identify them and renmove them
                        // from the current data
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

    /**-
     *  Validate if the current coach is registered in other club
     *
     * @param clubDto $clubDto
     * @param Club    $club
     *
     * @return int
     * 1: registered  0: not registered
     */
    private function isCoachRegistered(clubDto $clubDto, Club $club)
    {
        $result = 0;
        $proposalCoachId = (int) $clubDto->coach;
        $currentClubId = $club->getId();
        $currentCoach = $club->getCoach();
        // none coach assigned
        if (empty($proposalCoachId)){
            // echo 'no coach assigned';
            return 0;
        }
        // Find out the coach assigned club
        $proposalCoachClub = $this->coachManager->find($proposalCoachId)->getClub();
        $currentCoach = $club->getCoach();

        if (empty($proposalCoachClub))
        {
            // The coach is not assigned
            // echo 'The coach is not assigned';
            // Unassign the current club coach
            // $currentCoach = $club->getCoach();
            if (!empty($currentCoach)){
                // echo 'Unassign the current club coach';
                $currentCoach->setClub(null);
                $this->coachManager->persist($currentCoach);
            }
            return 0;
        } else{
            // Find out the current club coach and compare with the proposal
            // echo 'Find out the current club coach';
            if (!empty($currentCoach))
            {
                $currentCoachClub = $currentCoach->getClub();
                if ($proposalCoachClub != $currentCoachClub){
                    // echo 'The proposal club couch is different from current club';
                    return 1;
                }
            }
        }
        return $result;
    }
}
