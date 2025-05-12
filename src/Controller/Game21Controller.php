<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\Player;
use App\Card\GameLogic;
use App\Helper\CardGameHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Game21Controller extends AbstractController
{
    #[Route('/game', name: 'game', methods: ['GET'])]
    public function game(): Response
    {
        return $this->render('game21/game.html.twig');
    }

    #[Route('/game/doc', name: 'game_doc')]
    public function gameDoc(): Response
    {
        return $this->render('game21/doc.html.twig');
    }

    #[Route('/game', name: 'game_post', methods: ['POST'])]
    public function gameCallback(
        SessionInterface $session
    ): Response {
        // skapa spelet
        $game = new GameLogic();
        $game->shuffleDeck();
        $session->set('game', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/play', name: 'game_play', methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        if (!$game) {
            return $this->redirectToRoute('game');
        }

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $data = [
            "playerHand" => $player->getHand()->getHand(),
            "playerPoints" => $player->getPoints(),
            "bankHand" => $bank->getHand()->getHand(),
            "bankPoints" => $bank->getPoints(),
            "isGameOver" => $game->isGameOver(),
        ];

        return $this->render('game21/play.html.twig', $data);
    }

    #[Route('/game/draw', name: 'game_draw', methods: ['POST'])]
    public function gameDraw(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        if (!$game) {
            return $this->redirectToRoute('game');
        }

        $currentPoint = $game->playerDraw();
        if ($game->isOverLimit($currentPoint)) {
            $this->addFlash(
                'notice',
                'Du fick mer än 21 poäng, banken vann!'
            );

            $game->setGameOver();
        }

        $session->set('game', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/stop', name: 'game_stop', methods: ['POST'])]
    public function gameStop(
        SessionInterface $session
    ): Response {
        $game = $session->get('game');
        if (!$game) {
            return $this->redirectToRoute('game');
        }

        $game->bankDraw();
        $winner = $game->getWinner();

        $this->addFlash(
            'notice',
            "$winner vann!"
        );

        $game->setGameOver();

        $session->set('game', $game);

        return $this->redirectToRoute('game_play');
    }
}
