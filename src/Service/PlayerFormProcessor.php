<?php

namespace App\Service;

use App\Entity\Player;
use App\Form\Model\PlayerDto;
use App\Service\PlayerManager;
use App\Form\Type\PlayerFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

/**
 *
 * Processes the data player from the form
 * and if it successes save the data
 *
 */
class PlayerFormProcessor
{
    public function __construct(
        /**
         * The manager of the player repository
         */
        private readonly PlayerManager $playerManager,
        /**
         * The form factory
         */
        private readonly FormFactoryInterface $formFactory
    )
    {
    }

    public function __invoke(Player $player, Request $request): array
    {
        $playerDto = PlayerDto::createFromPlayer($player);

        $form = $this->formFactory->create(PlayerFormType::class, $playerDto);
        $form->handleRequest($request);

        // Validate the form submitted
        if (!$form->isSubmitted()) {
            return [null, 'Form is not valid'];
        }

        // Validate the form
        if($form->isValid() )
        {
            // Validating budget
            if ($this->isOverBudget($playerDto, $player)) {
                return [null, 'The budget has been exceeded'];
            }
            // Asigning values from DTO
            $player->setName($playerDto->name);
            $player->setSalary($playerDto->salary);
            $this->playerManager->save($player);
            $this->playerManager->reload($player);
            return [$player, null];
        }
        return [null, $form] ;
    }

    /**-
     *  Validate if the payroll exceeds the budget
     *
     * @param PlayerDto $playerDto
     * @param Player    $player
     *
     * @return int
     * 1: exceeds budget  0: under budget
     */
    private function isOverBudget(PlayerDto $playerDto, Player $player)
    {
        $currentSalary = $player->getSalary();
        $proposalSalary = $playerDto->salary;
        $club = $player->getClub();
        $result = 0;
        if(!empty($club)){
            $currentTotalSalaries = $club->getTotalSalaries();
            $budget = $club->getBudget();
            $proposalTotalSalaries = $currentTotalSalaries - ($currentSalary - $proposalSalary);
            $budgerDifference = $budget - $proposalTotalSalaries;
            if ($budgerDifference<0) {
                $result = 1;
            }
        }
        return $result;
    }
}

