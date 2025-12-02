<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;

class AnnulerRDVAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $rdvId = $args['id'] ?? null;

        if (!$rdvId) {
            $response->getBody()->write(json_encode(['error' => 'ID manquant'], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $this->serviceRDV->annulerRendezVous($rdvId);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['message' => 'Rendez-vous annulé'], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(202)->withHeader('Content-Type', 'application/json');
    }
}
