<?php

namespace App\Controller\Api;

use App\Service\ClubManager;
use App\Service\ClubFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class ClubController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/clubs")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        ClubManager $clubManager
    ) {
        return $clubManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/club")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        ClubManager $clubManager,
        ClubFormProcessor $clubFormProcessor,
        Request $request
    ) {
        $club = $clubManager->create();
        [$club, $error] = ($clubFormProcessor)($club, $request);
        $statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $club ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/club/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        ClubFormProcessor $clubFormProcessor,
        ClubManager $clubManager,
        Request $request
    ) {
        $club = $clubManager->find($id);
        if (!$club){
            return View::create('Club not found', Response::HTTP_BAD_REQUEST);
        }
        [$club, $error] = ($clubFormProcessor)($club, $request);
        $statusCode = $club ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $club ?? $error;
        return View::create($data, $statusCode);
   }
   
    /**
     * @Rest\Delete(path="/club/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int $id,
        ClubManager $clubManager
    ) {
        $club = $clubManager->find($id);
        if (!$club){
            return View::create('Club not found', Response::HTTP_BAD_REQUEST);
        }
        $clubManager->delete($club);
        return View::create(null, Response::HTTP_NO_CONTENT);
   }
}

