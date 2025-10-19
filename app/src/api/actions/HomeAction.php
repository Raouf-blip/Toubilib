<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'message' => "Bienvenue sur l'API Toubilib",
            'endpoints' => [
                "POST /auth/login/" => "Authentification de l'utilisateur",
                //test auth -> email:Denis.Teixeira@hotmail.fr mdp:test sur POSTMAN à mettre dans body en JSON {"email": "Denis.Teixeira@hotmail.fr","mdp": "test"}

                "GET /praticiens" => "Lister les praticiens",
                "GET /praticiens/{id}" => "Afficher les détails d'un praticien",
                "GET /praticiens/{id}/rdvs/occupes" => "Lister les créneaux occupés d'un praticien",
                "GET /praticiens/{id}/agenda?dateDebut=YYYY-MM-DD%2000:00:00&dateFin=YYYY-MM-DD%2023:59:59" => "Consulter l'agenda d'un praticien sur une période donnée, heures à préciser)",
                "GET /rdvs/{id}" => "Consulter un rendez-vous",
                "DELETE /rdvs/{id}" => "Annuler un rendez-vous"
            ]
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
