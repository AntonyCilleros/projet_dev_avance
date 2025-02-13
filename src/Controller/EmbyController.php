<?php

namespace App\Controller;

use AllowDynamicProperties;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

// requires full authentication
#[AllowDynamicProperties] #[Route('/api/emby', name: 'emby_')]
#[IsGranted('IS_AUTHENTICATED_FULLY')] // Vérifie que l'utilisateur est connecté
class EmbyController extends AbstractController
{
    // constructor qui récupère la clé API EMBY_API_KEY de l'env
    public function __construct()
    {
        $this->embyApiKey = $_ENV['EMBY_API_KEY'];
    }

     // requete get https://emby.velvet-room.tech/emby/Users/Query?IsHidden=true&IsDisabled=false&api_key=c8744e05b7ca46b890b9b6c4ccf01a42
    #[Route('/users/get_raytek_id', name: 'users_query', methods: ['GET'])]
    public function usersQuery(): Response
    {
        $url = 'https://emby.velvet-room.tech/emby/Users/Query?IsDisabled=false&api_key=' . $this->embyApiKey;
        $response = file_get_contents($url);
        // get $response.Items element where Name = RAYTEK
        $response = json_decode($response, true);
        $response = array_filter($response['Items'], function($item) {
            return $item['Name'] === 'RAYTEK';
        });
        // get id
        $response = array_values($response)[0]['Id'];
        return new Response($response);
    }

    #[Route('/shows/upcoming', name: 'shows_upcoming', methods: ['GET'])]
    public function showUpcoming(Request $request): Response
    {
        $start = max(0, (int) $request->query->get('start', 0));
        $end = (int) $request->query->get('end', 5);

        // get rayter id because apparently I need it
        $raytekId = $this->usersQuery()->getContent();

        // get upcoming shows
        $url = 'https://emby.velvet-room.tech/emby/Shows/Upcoming?UserId=' . $raytekId . '&EnableImages=false&api_key=' . $this->embyApiKey;
        $response = file_get_contents($url);
        $response = json_decode($response, true);

        // get their images
        $response['Items'] = array_slice($response['Items'], $start, $end-$start);

        // for each element in $response.Items, $response.Items.element.image = request get 'https://emby.velvet-room.tech/emby/Items/' . $response.Items.element.Id . '/RemoteImages?api_key=c8744e05b7ca46b890b9b6c4ccf01a42'
        $imagesSources = ['Id', 'SeasonId', 'SeriesId'];
        foreach ($response['Items'] as $key => $value) {
            // recherche d'abord une image pour l'épisode, puis pour la saison, puis pour la série (fallback)
            for ($i = 0; $i < 3; $i++) {
                $url = 'https://emby.velvet-room.tech/emby/Items/' . $value[$imagesSources[$i]] . '/RemoteImages?Limit=1&api_key=' . $this->embyApiKey;
                $responseImage = file_get_contents($url);
                $responseImage = json_decode($responseImage, true);
                if (count($responseImage['Images'])) {
                    $response['Items'][$key]['Image'] = $responseImage['Images'][0]['Url'];
                    break;
                }
            }
            // Si aucune image trouvée
            if (!isset($response['Items'][$key]['Image'])) {
                $response['Items'][$key]['Image'] = null;
            }
        }

        $response = json_encode($response);
        return new Response($response);
    }

}
