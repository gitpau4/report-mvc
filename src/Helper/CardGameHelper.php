<?php

namespace App\Helper;

use App\Card\DeckOfCards;
use App\Card\GameLogic;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameHelper
{
    public function getSessionDeck(
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

    public function formatJsonCards(
        array $cards
    ): array {
        $data = [];

        foreach ($cards as $card) {
            $data[] = [
                'value' => $card->getValue(),
                'suit' => $card->getSuit()
            ];
        }

        return $data;
    }

    public function getGameState(
        SessionInterface $session
    ): array {
        $data = [];

        $game = $session->get('game');
        if (!$game) {
            $data['notice'] = ['Inget pågående spel'];
            return $data;
        }

        $player = $game->getPlayer();
        $bank = $game->getBank();

        $data['player'] = [
            'hand' => $player->getHand()->getValues(),
            'points' => $player->getPoints(),
        ];

        $data['bank'] = [
            'hand' => $bank->getHand()->getValues(),
            'points' => $bank->getPoints(),
        ];

        return $data;
    }
}
