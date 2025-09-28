<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;

class GetRDVAction
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
            $response->getBody()->write(json_encode(['error' => 'ID manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $rdv = $this->serviceRDV->consulterRdv($rdvId);
        if (!$rdv) {
            $response->getBody()->write(json_encode(['error' => 'RDV non trouvé']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // accès aux propriétés publiques du DTO
        $response->getBody()->write(json_encode([
            'id' => $rdv->id,
            'praticienId' => $rdv->praticienId,
            'patientId' => $rdv->patientId,
            'patientEmail' => $rdv->patientEmail,
            'dateHeureDebut' => $rdv->dateHeureDebut,
            'dateHeureFin' => $rdv->dateHeureFin,
            'status' => $rdv->status,
            'duree' => $rdv->duree,
            'dateCreation' => $rdv->dateCreation,
            'motifVisite' => $rdv->motifVisite
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
