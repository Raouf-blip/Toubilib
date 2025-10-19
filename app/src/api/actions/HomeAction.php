<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'message' => 'Bienvenue sur l\'API Toubilib',
            'endpoints' => [
                'GET /praticiens' => 'Lister les praticiens',
                'GET /praticiens/{id}' => 'Afficher les détails d\'un praticien',
                'GET /praticiens/{id}/creneaux' => 'Lister les créneaux occupés d\'un praticien',
                "GET /praticiens/{id}/agenda?date_debut=YYYY-MM-DD%2000:00:00&date_fin=YYYY-MM-DD%2023:59:59" => "Consulter l'agenda d'un praticien sur une période donnée, heures à préciser pour inclure toute la journée)",
                'GET /rdvs/{id}' => 'Consulter un rendez-vous',
                "POST /rdvs/{id}" => "Annuler un rendez-vous"
            ]
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}