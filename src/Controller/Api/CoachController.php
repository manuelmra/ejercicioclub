<?php

namespace App\Controller\Api;

use App\Service\CoachManager;
use App\Service\CoachFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class CoachController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/coachs")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        CoachManager $coachManager
    ) {
        return $coachManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/coach")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        CoachManager $coachManager,
        CoachFormProcessor $coachFormProcessor,
        Request $request
    ) {
        $coach = $coachManager->create();
        [$coach, $error] = ($coachFormProcessor)($coach, $request);
        $statusCode = $coach ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $coach ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/coach/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        CoachFormProcessor $coachFormProcessor,
        CoachManager $coachManager,
        Request $request
    ) {
        $coach = $coachManager->find($id);
        if (!$coach){
            return View::create('Coach not found', Response::HTTP_BAD_REQUEST);
        }
        [$coach, $error] = ($coachFormProcessor)($coach, $request);
        $statusCode = $coach ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $coach ?? $error;
        return View::create($data, $statusCode);
   }
   
    /**
     * @Rest\Delete(path="/coach/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"coach"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int $id,
        CoachManager $coachManager
    ) {
        $coach = $coachManager->find($id);
        if (!$coach){
            return View::create('Coach not found', Response::HTTP_BAD_REQUEST);
        }
        $coachManager->delete($coach);
        return View::create(null, Response::HTTP_NO_CONTENT);
   }
}

