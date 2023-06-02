<?php

namespace App\Service;

use App\Entity\Coach;
use App\Form\Model\CoachDto;
use App\Service\CoachManager;
use App\Form\Model\PlayerDto;
use App\Form\Type\CoachFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class CoachFormProcessor
{
    private $coachManager;
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
        if (!$form->isSubmitted()) {
            return [null, 'Form is not valid'];
        }
        if($form->isValid() )
        {
            $coach->setName($coachDto->name);
            $coach->setSalary($coachDto->salary);
            $coach->setClub($coachDto->club);
            $this->coachManager->save($coach);
            $this->coachManager->reload($coach);
            return [$coach, null];
        }
        return [null, $form] ;
    }
}

