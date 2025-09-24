<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDV;

class GetRDVAction
{
    private ServiceRDV $serviceRDV;

    public function __construct(ServiceRDV $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $rdvId = $args['id'];
        $rdvDTO = $this->serviceRDV->consulterRdv($rdvId);

        if (!$rdvDTO) {
            $response->getBody()->write(json_encode(['error' => 'RDV not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($rdvDTO));
        return $response->withHeader('Content-Type', 'application/json');
    }
}