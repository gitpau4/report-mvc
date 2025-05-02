<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameControllerJson
{
    private function getSessionDeck(
        SessionInterface $session
    ): DeckOfCards {
        if ($session->has('deck')) {
            $deck = $session->get('deck');
            if ($deck instanceof DeckOfCards) {
                return $deck;
            }
        }

        // skapa kortlek om inte finns i session
        $deck = new DeckOfCards();
        $session->set('deck', $deck);
        return $deck;
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): Response {
        $deck = $this->getSessionDeck($session);

        $cards = $deck->getOriginalDeck();
        $data = [];

        foreach ($cards as $card) {
            $data[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST'])]
    public function apiDeckShuffle(
        SessionInterface $session
    ): Response {
        $deck = $this->getSessionDeck($session);

        // återställ kortlek
        $deck->reset();

        // blanda kort
        $deck->shuffle();

        $cards = $deck->getDeck();
        $data = [];

        foreach ($cards as $card) {
            $data[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit()
            ];
        }

        $session->set('deck', $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST'])]
    public function apiDeckDraw(
        SessionInterface $session
    ): Response {
        $deck = $this->getSessionDeck($session);

        // dra ett kort
        $drawnCard = $deck->drawCard();
        $data = [];

        // phpmd säger att undvika else satser, men i detta fall tycker jag det blir tydligare och snyggare med else satsen.
        if ($drawnCard === null) {
            $data['warning'] = ['Det finns inga kort kvar i leken!'];
        } else {
            $data['card'] = [
                'value' => $drawnCard->getValue(),
                'suit' => $drawnCard->getSuit()
            ];
        }

        $session->set('deck', $deck);

        $data['cardsLeft'] = $deck->getNumberOfCards();

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_deck_draw_num", methods: ['POST'])]
    public function apiDeckDrawNum(
        SessionInterface $session,
        int $num
    ): Response {
        $deck = $this->getSessionDeck($session);

        $cards = $deck->drawNumberCards($num);

        $data = [];

        if (empty($cards)) {
            $data['warning'] = ['Det finns inte tillräckligt många kort kvar i leken!'];
        } else {
            foreach ($cards as $card) {
                $data['cards'][] = [
                    'value' => $card->getValue(),
                    'suit' => $card->getSuit()
                ];
            }
        }

        $session->set('deck', $deck);

        $data['cardsLeft'] = $deck->getNumberOfCards();

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
