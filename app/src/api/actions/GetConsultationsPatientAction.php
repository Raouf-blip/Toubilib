<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\core\application\services\HATEOASService;

class GetConsultationsPatientAction
{
    private ServiceRDVInterface $serviceRDV;
    private ServicePraticienInterface $servicePraticien;
    private HATEOASService $hateoasService;

    public function __construct(ServiceRDVInterface $serviceRDV,ServicePraticienInterface $servicePraticien,HATEOASService $hateoasService)
    {
        $this->serviceRDV = $serviceRDV;
        $this->servicePraticien = $servicePraticien;
        $this->hateoasService = $hateoasService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $patientId = $args['id'] ?? null;
        if (!$patientId) {
            $response->getBody()->write(json_encode(['error' => 'ID manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $consultations = $this->serviceRDV->listerConsultationsPatient($patientId);
        if (!$patientId) {
            $response->getBody()->write(json_encode(['error' => 'RDV non trouvé']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $data = [];
        foreach ($consultations as $consultation) {
            $praticien = $this->servicePraticien->RecherchePraticienByID($consultation->praticienId);
            $data[] = [
                'nom praticien' => $praticien->nom,
                'prenom praticien' => $praticien->prenom,
                'dateHeureDebut' => $consultation->dateHeureDebut,
                'dateHeureFin' => $consultation->dateHeureFin,
                'motifVisite' => $consultation->motifVisite,
                'duree' => $consultation->duree,
                'status' => $consultation->status,
                'dateCreation' => $consultation->dateCreation,
                '_links' => $this->hateoasService->getRDVLinks($consultation->id)
            ];
        }
        $responseData = [
            'praticiens' => $data,
            '_links' => $this->hateoasService->getPraticiensListLinks()
        ];

        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
