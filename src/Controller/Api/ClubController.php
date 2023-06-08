<?php

namespace App\Controller\Api;

use App\Service\ClubManager;
use App\Service\ClubFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class ClubController extends AbstractFOSRestController
{
    /**
     *
     * This action shows all the clubs with the following data:
     *  - Name of the club
     *  - Its budget
     *  - Its coach
     *  - The players that are in the club
     *
     * @Rest\Get(path="/clubs")
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        ClubManager $clubManager
    ) {
        return $clubManager->getRepository()->findAll();
    }

    /**
     *
     * This action shows only the data of one club:
     *  - Name of the club
     *  - Its budget
     *  - Its coach
     *  - The players that are in the club
     *
     * @Rest\Get(path="/club/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"laliga"}, serializerEnableMaxDepthChecks=true)
     */
    public function showAction(
        int $id,
        ClubManager $clubManager
    ) {
        return $clubManager->getRepository()->find($id);
    }

    /**
     *
     * This action creates a club.
     *
     *  - Name of the club   -> Mandatory
     *  - Its budget         -> Mandatory
     *  - Its coach          -> Optional
     *    If you want to add a coach you set he coach id
     *    If you want you can ommit it
     *  - The players        -> Optional
     *     * It can add players that are currently in the DB
     *       In this case It's needed to set the id of the player
     *     * It can add new players
     *       You don't have to set the id of the player (meaning it's a new player)
     *       It must be set all the data about the player
     *       - Name
     *       - Salary
     *
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
     *
     * This action modifies the fields of a club.
     *
     *  - Name of the club   -> Mandatory
     *  - Its budget         -> Mandatory
     *  - Its coach          -> Optional
     *    If you want to add a coach you set he coach id
     *    If you want you can ommit it and the club won't have a coach
     *  - The players        -> Optional
     *     * It can add players that are currently in the DB
     *       In this case It's needed to set the id of the player
     *     * It can add new players
     *       You don't have to set the id of the player (meaning it's a new player)
     *       It must be set all the data about the player
     *       - Name
     *       - Salary
     *
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
     *
     *   This action was commented to be developed later because its complexity
     *
     * @Rest\Delete(path="/club/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"club"}, serializerEnableMaxDepthChecks=true)
     */
//     public function deleteAction(
//         int $id,
//         ClubManager $clubManager
//     ) {
//         $club = $clubManager->find($id);
//         if (!$club){
//             return View::create('Club not found', Response::HTTP_BAD_REQUEST);
//         }
//         $clubManager->delete($club);
//         return View::create(null, Response::HTTP_NO_CONTENT);
//    }
}

