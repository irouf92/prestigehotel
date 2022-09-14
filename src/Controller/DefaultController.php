<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('default/home.html.twig', [
           
        ]);
    }

    #[Route('/mentions-légales', name: 'mentionslegales')]
    public function mentions(): Response
    {
        return $this->render('otherlinks/mentionslegales.html.twig', [
           
        ]);
    }

    #[Route('/conditions-général-de-vente', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('otherlinks/cgv.html.twig', [
           
        ]);
    }
}
