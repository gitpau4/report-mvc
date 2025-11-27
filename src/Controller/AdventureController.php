<?php

namespace App\Controller;

use App\Adventure\AdventureLogic;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureController extends AbstractController
{
    #[Route('/proj', name: 'adventure', methods: ['GET'])]
    public function adventure(): Response
    {
        return $this->render('proj/adventure.html.twig');
    }

    #[Route('/proj/about', name: 'proj_about', methods: ['GET'])]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route('/proj/cheat', name: 'proj_cheat', methods: ['GET'])]
    public function projCheat(): Response
    {
        return $this->render('proj/cheat.html.twig');
    }

    #[Route('/proj', name: 'adventure_post', methods: ['POST'])]
    public function adventureCallback(
        SessionInterface $session
    ): Response {
        $adventure = new AdventureLogic(__DIR__ . '/../../data/adventure_rooms.json');
        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }

    #[Route('/proj/play', name: 'adventure_play', methods: ['GET'])]
    public function adventurePlay(
        SessionInterface $session
    ): Response {
        $adventure = $session->get('adventure');
        if (!$adventure) {
            return $this->redirectToRoute('adventure');
        }

        $data = [
            "currentRoom" => $adventure->getCurrentRoom(),
            "isGameOver" => $adventure->getIsGameOver(),
            "inventory" => $adventure->getInventoryAsString()
        ];

        return $this->render('proj/play.html.twig', $data);
    }

    #[Route('/proj/move', name: 'adventure_move', methods: ['POST'])]
    public function adventureMove(
        SessionInterface $session,
        Request $request
    ): Response {
        $adventure = $session->get('adventure');
        if (!$adventure) {
            return $this->redirectToRoute('adventure');
        }

        $direction = $request->request->get('move');

        $moveMsg = $adventure->move($direction);

        $this->addFlash(
            'adventure',
            $moveMsg
        );

        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }

    #[Route('/proj/pick', name: 'adventure_pick', methods: ['POST'])]
    public function adventurePick(
        SessionInterface $session,
        Request $request
    ): Response {
        $adventure = $session->get('adventure');
        if (!$adventure) {
            return $this->redirectToRoute('adventure');
        }

        $itemName = $request->request->get('pick');
        $itemName = str_replace("Pick up ", "", $itemName);

        $pickMsg = $adventure->pickItem($itemName);

        $this->addFlash(
            'adventure',
            $pickMsg
        );

        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }

    #[Route('/proj/action', name: 'adventure_action', methods: ['POST'])]
    public function adventureAction(
        SessionInterface $session
    ): Response {
        $adventure = $session->get('adventure');
        if (!$adventure) {
            return $this->redirectToRoute('adventure');
        }

        $actionMsg = $adventure->interact();

        $this->addFlash(
            'adventure',
            $actionMsg
        );

        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }
}
