<?php

namespace App\Service;

use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Service\PlayerManager;
use App\Form\Type\PlayerFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class PlayerFormProcessor
{
    private $playerManager;
    private $formFactory;

    public function __construct(
        PlayerManager $playerManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->playerManager = $playerManager;
        $this->formFactory = $formFactory;
        }

    public function __invoke(Player $player, Request $request): array
    {
        $playerDto = PlayerDto::createFromPlayer($player);

        $form = $this->formFactory->create(PlayerFormType::class, $playerDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not valid'];
        }
        if($form->isValid() )
        {
            $player->setName($playerDto->name);
            $player->setSalary($playerDto->salary);
            $player->setClub($playerDto->club);
            $this->playerManager->save($player);
            $this->playerManager->reload($player);
            return [$player, null];
        }
        return [null, $form] ;
    }
}

