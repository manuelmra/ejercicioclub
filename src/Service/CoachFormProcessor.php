<?php

namespace App\Service;

use App\Entity\Coach;
use App\Form\Model\CoachDto;
use App\Service\CoachManager;
use App\Form\Model\PlayerDto;
use App\Form\Type\CoachFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

/**
 *
 * Processes the data player from the form
 * and if it successes save the data
 *
 */
class CoachFormProcessor
{
    /**
     * The manager of the coach repository
     *
     * @var CoachManager
     */
    private $coachManager;

    /**
     * The form factory
     *
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(
        CoachManager $coachManager,
        FormFactoryInterface $formFactory
    )
    {
        $this->coachManager = $coachManager;
        $this->formFactory = $formFactory;
        }

    public function __invoke(Coach $coach, Request $request): array
    {
        $coachDto = CoachDto::createFromCoach($coach);

        $form = $this->formFactory->create(CoachFormType::class, $coachDto);
        $form->handleRequest($request);

        // Validate the form submitted
        if (!$form->isSubmitted()) {
            return [null, 'Form is not valid'];
        }

        // Validate the form
        if($form->isValid() )
        {
        // Validating budget
            if ($this->isOverBudget($coachDto, $coach)) {
                return [null, 'The budget has been exceeded'];
            }

            $coach->setName($coachDto->name);
            $coach->setSalary($coachDto->salary);
            $this->coachManager->save($coach);
            $this->coachManager->reload($coach);
            return [$coach, null];
        }
        return [null, $form] ;
    }

    /**-
     *  Validate if the payroll exceeds the budget
     *
     * @param CoachDto $coachDto
     * @param Coach    $coach
     *
     * @return int
     * 1: exceeds budget  0: under budget
     */
    private function isOverBudget(CoachDto $coachDto, Coach $coach)
    {
        $currentSalary = $coach->getSalary();
        $proposalSalary = $coachDto->salary;
        $club = $coach->getClub();
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

