<?php

namespace App\Controller;

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
}
