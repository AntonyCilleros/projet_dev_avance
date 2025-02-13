<?php

namespace App\Controller;

use AllowDynamicProperties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

// requires full authentication
#[AllowDynamicProperties] #[Route('/api/languages', name: 'languages_')]
class LanguagesController extends AbstractController
{
    // constructor qui récupère la clé API EMBY_API_KEY de l'env
    public function __construct()
    {
        $this->symfonyApiHost = $_ENV['SYMFONY_API_HOST'];
    }

     // requete get https://emby.velvet-room.tech/emby/Users/Query?IsHidden=true&IsDisabled=false&api_key=c8744e05b7ca46b890b9b6c4ccf01a42
    #[Route('/', name: 'languages', methods: ['GET'])]
    public function languages(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $url = $this->symfonyApiHost . '/api/languages?page=' . $page;
        $response = file_get_contents($url);
        return new Response($response);
    }

}
