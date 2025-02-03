<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    // attrape toutes les routes GET sauf celles commençant par /api/
    #[Route('/{vueRouting}', name: 'app', requirements: ['vueRouting' => '^(?!api/).+'], methods: ['GET'])]
    public function index(): Response
    {
        // Rend le fichier app.html.twig, qui contient le conteneur Vue.js
        return $this->render('app.html.twig');
    }
}
