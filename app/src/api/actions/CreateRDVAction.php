<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;
use Slim\Psr7\Response as SlimResponse;

class CreateRDVAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $dto = $request->getAttribute('inputRdvDto');
        if (! $dto) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['error' => 'Input DTO manquant (middleware absent?)']));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        try {
            $rdv = $this->serviceRDV->creerRendezVous($dto);

            $out = [
                'id' => $rdv->getId(),
                'praticienId' => $rdv->getPraticienId(),
                'patientId' => $rdv->getPatientId(),
                'patientEmail' => $rdv->getPatientEmail(),
                'dateHeureDebut' => $rdv->getDateHeureDebut()->format('Y-m-d H:i:s'),
                'dateHeureFin' => $rdv->getDateHeureFin() ? $rdv->getDateHeureFin()->format('Y-m-d H:i:s') : null,
                'duree' => $rdv->getDuree(),
                'motifVisite' => $rdv->getMotifVisite(),
                'dateCreation' => $rdv->getDateCreation() ? $rdv->getDateCreation()->format('Y-m-d H:i:s') : null,
                'status' => $rdv->getStatus()
            ];

            $res = new SlimResponse();
            $res->getBody()->write(json_encode($out, JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (\Exception $e) {
            $status = 400;
            $msg = $e->getMessage();

            if (stripos($msg, 'occup') !== false) $status = 409;
            if (stripos($msg, 'inexistant') !== false) $status = 404;

            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus($status);
        }
    }
}
