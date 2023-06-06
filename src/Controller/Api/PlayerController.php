<?php

namespace App\Controller\Api;

use App\Service\PlayerManager;
use App\Service\PlayerFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class PlayerController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/players")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        PlayerManager $playerManager
    ) {
        return $playerManager->getRepository()->findAll();
    }

    /**
     * @Rest\Get(path="/player/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function showAction(
        int $id,
        PlayerManager $playerManager
    ) {
        return $playerManager->getRepository()->find($id);
    }

    /**
     * @Rest\Post(path="/player")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        PlayerManager $playerManager,
        PlayerFormProcessor $playerFormProcessor,
        Request $request
    ) {
        $player = $playerManager->create();
        [$player, $error] = ($playerFormProcessor)($player, $request);
        $statusCode = $player ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $player ?? $error;
        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/player/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        PlayerFormProcessor $playerFormProcessor,
        PlayerManager $playerManager,
        Request $request
    ) {
        $player = $playerManager->find($id);
        if (!$player){
            return View::create('Player not found', Response::HTTP_BAD_REQUEST);
        }
        [$player, $error] = ($playerFormProcessor)($player, $request);
        $statusCode = $player ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $player ?? $error;
        return View::create($data, $statusCode);
   }
   
    /**
     * @Rest\Delete(path="/player/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(
        int $id,
        PlayerManager $playerManager
    ) {
        $player = $playerManager->find($id);
        if (!$player){
            return View::create('Player not found', Response::HTTP_BAD_REQUEST);
        }
        $playerManager->delete($player);
        return View::create(null, Response::HTTP_NO_CONTENT);
   }
}

