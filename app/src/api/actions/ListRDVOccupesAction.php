<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDV;

class ListRDVOccupesAction
{
    private ServiceRDV $serviceRDV;

    public function __construct(ServiceRDV $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $praticienId = (int)$args['id'];
        $params = $request->getQueryParams();
        $debut = new \DateTime($params['dateDebut']);
        $fin = new \DateTime($params['dateFin']);

        $slots = $this->serviceRDV->listerCreneauxOccupes($praticienId, $debut, $fin);

        $response->getBody()->write(json_encode($slots));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
