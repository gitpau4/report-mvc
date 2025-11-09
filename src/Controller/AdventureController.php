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
    // kommer behöva lägga till img path i json filen

    #[Route('/proj', name: 'adventure_post', methods: ['POST'])]
    public function adventureCallback(
        SessionInterface $session
    ): Response {
        $adventure = new AdventureLogic();
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

        // kanske göra egen styling för adventure flash, då byt ut notice till typ adventure, kanske två olika färger, gul och blå eller röd och grön
        $this->addFlash(
            'notice',
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
        $itemName = str_replace("Pick ", "", $itemName);

        $pickMsg = $adventure->pickItem($itemName);

        $this->addFlash(
            'notice',
            $pickMsg
        );

        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }

    #[Route('/proj/action', name: 'adventure_action', methods: ['POST'])]
    public function adventureAction(
        SessionInterface $session,
        Request $request
    ): Response {
        $adventure = $session->get('adventure');
        if (!$adventure) {
            return $this->redirectToRoute('adventure');
        }

        $action = $request->request->get('action');
        $action = str_replace("Interact with ", "", $action);

        $actionMsg = $adventure->interact($action);

        $this->addFlash(
            'notice',
            $actionMsg
        );

        $session->set('adventure', $adventure);

        return $this->redirectToRoute('adventure_play');
    }
}
