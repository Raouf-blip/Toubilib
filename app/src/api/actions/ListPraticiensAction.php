<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServicePraticienInterface;

class ListPraticiensAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $praticiens = $this->servicePraticien->listerPraticiens();
        $data = [];
        foreach ($praticiens as $praticien) {
            $data[] = [
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'ville' => $praticien->ville,
                'email' => $praticien->email,
                'specialite' => $praticien->specialite
            ];
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}

