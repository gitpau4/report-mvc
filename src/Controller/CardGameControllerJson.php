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
    ): DeckOfCards
    {
        if ($session->has('deck')) {
            $deck = $session->get('deck');
        } else {
            // skapa kortlek om inte finns i session
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }

        return $deck;
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): Response
    {
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
}
