<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;

class AgendaPraticienAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $praticienId = $args['id'] ?? null;
        if (!$praticienId) {
            $response->getBody()->write(json_encode(['error' => 'ID du praticien manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $query = $request->getQueryParams();
        $dateDebut = isset($query['dateDebut']) ? new \DateTime($query['dateDebut']) : null;
        $dateFin = isset($query['dateFin']) ? new \DateTime($query['dateFin']) : null;

        $agenda = $this->serviceRDV->getAgendaPraticien($praticienId, $dateDebut, $dateFin);

        $response->getBody()->write(json_encode($agenda));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
