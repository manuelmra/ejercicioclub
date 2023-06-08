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
     *
     * This action shows all the players with the following data:
     *  - Name of the player
     *  - His salary
     *
     * @Rest\Get(path="/players")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        PlayerManager $playerManager
    ) {
        return $playerManager->getRepository()->findAll();
    }

    /**
     *
     * This action shows only the data of one player:
     *  - Name of the player
     *  - His salary
     *
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
     *
     * This action creates a player.
     *  - Name of the player   -> Mandatory
     *  - Its salary           -> Mandatory
     *
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
     *
     * This action modifies the fields of a player.
     *  - Name of the player  -> Mandatory
     *  - His salary          -> Mandatory
     *
     * @Rest\Post(path="/playerupdate/{id}", requirements={"id"="\d+"})
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
     *
     * This action removes a player from a club.
     *    It will set null in the club field table
     *
     * @Rest\Post(path="/dropplayer/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"player"}, serializerEnableMaxDepthChecks=true)
     */
    public function dropAction(
        int $id,
        PlayerManager $playerManager,
        PlayerFormProcessor $playerFormProcessor,
        Request $request
    ) {
        $player = $playerManager->find($id);
        if (!$player){
            return View::create('Player not found', Response::HTTP_BAD_REQUEST);
        }
        $player->setClub(null);
        [$player, $error] = ($playerFormProcessor)($player, $request);
        $statusCode = $player ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $player ?? $error;
        return View::create($data, $statusCode);
   }
}

