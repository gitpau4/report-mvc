<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Helper\CardGameHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    private CardGameHelper $cardGameHelper;

    public function __construct(CardGameHelper $cardGameHelper)
    {
        $this->cardGameHelper = $cardGameHelper;
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
        $deck = $this->cardGameHelper->getSessionDeck($session);

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
        $deck = $this->cardGameHelper->getSessionDeck($session);

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
        $deck = $this->cardGameHelper->getSessionDeck($session);

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
        $deck = $this->cardGameHelper->getSessionDeck($session);

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
}
