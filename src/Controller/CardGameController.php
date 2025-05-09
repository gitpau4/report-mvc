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

class CardGameController extends AbstractController
{
    private CardGameHelper $helper;

    public function __construct(CardGameHelper $helper)
    {
        $this->helper = $helper;
    }

    #[Route('/session', name: 'session')]
    public function session(
        SessionInterface $session
    ): Response {
        $data = [
            "sessionData" => $session->all()
        ];

        return $this->render('session.html.twig', $data);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function sessionDelete(
        SessionInterface $session
    ): Response {
        $session->clear();

        $this->addFlash(
            'notice',
            'Sessionen är nu raderad'
        );

        return $this->redirectToRoute('session');
    }

    #[Route('/card', name: 'card')]
    public function card(): Response
    {
        return $this->render('card/card.html.twig');
    }

    #[Route('/card/deck', name: 'deck')]
    public function deck(
        SessionInterface $session
    ): Response {
        $deck = $this->helper->getSessionDeck($session);

        $data = [
            "cards" => $deck->getOriginalDeck(),
            "title" => 'Samtliga kort'
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route('/card/deck/shuffle', name: 'deck_shuffle')]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        $deck = $this->helper->getSessionDeck($session);

        // återställ kortlek
        $deck->reset();

        // blanda kort
        $deck->shuffle();

        $session->set('deck', $deck);

        $data = [
            "cards" => $deck->getDeck(),
            "title" => 'Blandad kortlek'
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route('/card/deck/draw', name: 'deck_draw')]
    public function deckDraw(
        SessionInterface $session
    ): Response {
        $deck = $this->helper->getSessionDeck($session);

        // dra ett kort
        $cards = [];
        $drawnCard = $deck->drawCard();

        if ($drawnCard === null) {
            $this->addFlash(
                'warning',
                'Det finns inga kort kvar i leken!'
            );
        } else {
            $cards[] = $drawnCard;
        }

        $session->set('deck', $deck);

        $cardsNum = $deck->getNumberOfCards();

        $data = [
            "cards" => $cards,
            "cards_num" => $cardsNum,
            "title" => 'Ett draget kort'
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route('/card/deck/draw/{num<\d+>}', name: 'deck_draw_num')]
    public function deckDrawNumb(
        SessionInterface $session,
        int $num
    ): Response {
        $deck = $this->helper->getSessionDeck($session);

        // dra num kort
        $cards = $deck->drawNumberCards($num);
        if (empty($cards)) {
            $this->addFlash(
                'warning',
                'Det finns inte tillräckligt många kort kvar i leken!'
            );
        }

        $session->set('deck', $deck);

        $cardsNum = $deck->getNumberOfCards();

        $data = [
            "cards" => $cards,
            "cards_num" => $cardsNum,
            "title" => "$num dragna kort"
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    // game 21
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
        Request $request,
        SessionInterface $session
    ): Response
    {
        // skapa spelet
        $game = new GameLogic();
        $game->shuffleDeck();
        $session->set('game', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route('/game/play', name: 'game_play', methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response
    {
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
    ): Response
    {
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
    ): Response
    {
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
