<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController
{
    #[Route('/lucky/number')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Very lucky number: '.$number.'</body></html>'
        );
    }

    #[Route("/lucky/hello")]
    public function hello(): Response
    {
        return new Response(
            '<html><body>Hello to you!</body></html>'
        );
    }
}
