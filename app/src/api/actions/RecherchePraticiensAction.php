<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServicePraticienInterface;

class RecherchePraticiensAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }
    
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'ID du praticien requis'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }

        $praticien = $this->servicePraticien->RecherchePraticienByID($id);
        
        if ($praticien === null) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Praticien non trouvé'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }
        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => [
                'id' => $praticien->id,
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'ville' => $praticien->ville,
                'email' => $praticien->email,
                'telephone' => $praticien->telephone,
                'specialite' => $praticien->specialite,
                'structureNom' => $praticien->structureNom,
                'adresse' => $praticien->adresse,
                'codePostal' => $praticien->codePostal,
                'structureVille' => $praticien->structureVille,
                'motifsVisite' => $praticien->motifsVisite,
                'moyensPaiement' => $praticien->moyensPaiement
            ]
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}