<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api/quote", name: "json_quote")]
    public function jsonQuote(): Response
    {
        $quotes = [
            "Oroa dig inte för morgondagen, i övermorgon är den över. - Voltaire",
            "Alla dessa dagar som kom och gick, inte visste jag att de var livet. - Stig Johansson",
            "Samhället är människans verk - om något är fel kan vi ändra på det. - Olof Palme"
        ];

        $randNum = random_int(0, 2);
        $randQuote = $quotes[$randNum];

        date_default_timezone_set("Europe/Stockholm");
        $dateToday = date("Y-m-d");

        $timestamp = date("H:i:s");

        $data = [
            'citat' => $randQuote,
            'dagens datum' => $dateToday,
            'tidsstämpel' => $timestamp
        ];

        // $response = new Response();
        // $response->setContent(json_encode($data));
        // $response->headers->set('Content-Type', 'application/json');

        // return $response;

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
