<?php

namespace App\Service;

use App\Entity\Club;
use App\Form\Model\ClubDto;
use App\Service\ClubManager;
use App\Form\Model\PlayerDto;
use App\Form\Type\ClubFormType;
use App\Service\PlayerManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

class ClubFormProcessor
{
    private $clubManager;
    private $playerManager;
    private $formFactory;

    public function __construct(
        ClubManager $clubManager,
        PlayerManager $playerManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->clubManager = $clubManager;
        $this->playerManager = $playerManager;
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
}
