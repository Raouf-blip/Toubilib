<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;

class MarquerRDVHonoreAction
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
            $this->serviceRDV->marquerCommeHonore($rdvId);
        } catch (\Exception $e) {
            $status = 400;
            $msg = $e->getMessage();
            
            // Gérer les erreurs spécifiques
            if (strpos($msg, 'inexistant') !== false) {
                $status = 404;
            }
            
            $response->getBody()->write(json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE));
            return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['message' => 'Rendez-vous marqué comme honoré'], JSON_UNESCAPED_UNICODE));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}

