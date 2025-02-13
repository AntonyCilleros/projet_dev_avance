<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
     // attrape toutes les routes mais a une priorité -1
    #[Route('/{req}', name: 'app', requirements: ['req' => '.*'], defaults: ['req' => null], methods: ['GET'], priority: -1)]
    public function index(): Response
    {
        // Rend le fichier app.html.twig, qui contient le conteneur Vue.js. data pour envoyer des données à Vue.js
        return $this->render('app.html.twig', [
            'data' => json_encode([])
        ]);
    }
}
