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
}