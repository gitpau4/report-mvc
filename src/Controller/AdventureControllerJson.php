<?php

namespace App\Controller;

use App\Adventure\AdventureLogic;
use App\Adventure\Room;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureControllerJson extends AbstractController
{
    private function jsonResponse(
        array $data
    ): JsonResponse {
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/api/", name: "proj_api")]
    public function projApi(): Response
    {
        return $this->render('proj/proj_api.html.twig');
    }

    private function getRoomData(
        Room $room
    ): array {
        $data = [
            "id" => $room->getRoomId(),
            "description" => $room->getDescription(),
            "img" => $room->getImg(),
            "exits" => $room->getExits(),
            "items" => array_map(function ($item) {
                return [
                    "name" => $item->getName(),
                    "description" => $item->getDescription(),
                    "canTake" => $item->canTakeItem()
                ];
            }, $room->getItems()),
            "action" => $room->getAction()
        ];

        return $data;
    }

    #[Route("/proj/api/rooms", name: "proj_api_rooms", methods: ['GET'])]
    public function projApiRooms(
    ): Response {
        $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
        $rooms = $adventure->getRooms();

        $roomData = [];
        foreach ($rooms as $room) {
            $roomData[] = $this->getRoomData($room);
        }

        return $this->jsonResponse($roomData);
    }

    #[Route("/proj/api/room/{id}", name: "proj_api_room_id", methods: ['GET'])]
    public function projApiRoomId(
        int $id
    ): Response {
        $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
        $rooms = $adventure->getRooms();

        if (!isset($rooms[$id])) {
            $data = [
                "error" => "No room with this id"
            ];
            return $this->jsonResponse($data);
        }

        $roomData = $this->getRoomData($rooms[$id]);

        return $this->jsonResponse($roomData);
    }

    #[Route("/proj/api/inventory", name: "proj_api_inventory", methods: ['GET'])]
    public function projApiInventory(
        SessionInterface $session
    ): Response {

        // hämta spelet från sessionen ifall det startats
        $adventure = $session->get("adventure");

        if (!$adventure) {
            $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
            $session->set("adventure", $adventure);
        }

        $inventory = $adventure->getPlayer()->getInventory();

        $invData = [];
        foreach ($inventory as $item) {
            $invData[] = [
                "name" => $item->getName(),
                "description" => $item->getDescription(),
                "canTake" => $item->canTakeItem()
            ];
        }

        return $this->jsonResponse($invData);
    }

    #[Route("/proj/api/move/{direction}", name: "proj_api_move", methods: ['POST'])]
    public function projApiMove(
        SessionInterface $session,
        string $direction
    ): Response {
        $adventure = $session->get("adventure");

        if (!$adventure) {
            $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
            $session->set("adventure", $adventure);
        }

        $moveMsg = $adventure->move($direction);
        $currentRoomId = $adventure->getCurrentRoom()->getRoomId();

        $data = [
            "message" => $moveMsg,
            "direction" => $direction,
            "current room id" => $currentRoomId
        ];

        return $this->jsonResponse($data);
    }

    #[Route("/proj/api/pick/{item}", name: "proj_api_pick", methods: ['POST'])]
    public function projApiPick(
        SessionInterface $session,
        string $item
    ): Response {
        $adventure = $session->get("adventure");

        if (!$adventure) {
            $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
            $session->set("adventure", $adventure);
        }

        $pickMsg = $adventure->pickItem($item);

        $data = [
            "message" => $pickMsg,
            "item" => $item
        ];

        return $this->jsonResponse($data);
    }

    #[Route("/proj/api/action", name: "proj_api_action", methods: ['POST'])]
    public function projApiAction(
        SessionInterface $session
    ): Response {
        $adventure = $session->get("adventure");

        if (!$adventure) {
            $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
            $session->set("adventure", $adventure);
        }

        $actionMsg = $adventure->interact();

        $data = [
            "message" => $actionMsg
        ];

        return $this->jsonResponse($data);
    }
}
