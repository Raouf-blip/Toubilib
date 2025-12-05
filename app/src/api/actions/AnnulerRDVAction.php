<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;
use toubilib\core\application\services\HATEOASService;

class AnnulerRDVAction
{
    private ServiceRDVInterface $serviceRDV;
    private HATEOASService $hateoasService;

    public function __construct(ServiceRDVInterface $serviceRDV, HATEOASService $hateoasService)
    {
        $this->serviceRDV = $serviceRDV;
        $this->hateoasService = $hateoasService;
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

        // Récupérer le RDV pour obtenir les IDs nécessaires aux liens
        $rdv = $this->serviceRDV->consulterRdv($rdvId);
        $responseData = [
            'message' => 'Rendez-vous annulé',
            '_links' => $this->hateoasService->getRDVLinks($rdvId)
        ];
        
        if ($rdv) {
            $responseData['_links']['praticien'] = [
                'href' => "{$this->hateoasService->getBaseUrl()}/praticiens/{$rdv->praticienId}",
                'method' => 'GET',
                'description' => 'Détails du praticien'
            ];
            $responseData['_links']['patient'] = [
                'href' => "{$this->hateoasService->getBaseUrl()}/patients/{$rdv->patientId}",
                'method' => 'GET',
                'description' => 'Détails du patient'
            ];
        }
        
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
        return $response->withStatus(202)->withHeader('Content-Type', 'application/json');
    }
}
