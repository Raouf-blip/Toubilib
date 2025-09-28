<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServicePatient;

class GetPatientAction
{
    private ServicePatient $servicePatient;

    public function __construct(ServicePatient $servicePatient)
    {
        $this->servicePatient = $servicePatient;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $patientId = $args['id'] ?? null;
        if (!$patientId) {
            $response->getBody()->write(json_encode(['error' => 'ID manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $patient = $this->servicePatient->consulterPatient($patientId);
        if (!$patient) {
            $response->getBody()->write(json_encode(['error' => 'Patient non trouvé']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'id' => $patient->getId(),
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'dateNaissance' => $patient->getDateNaissance()?->format('Y-m-d'),
            'adresse' => $patient->getAdresse(),
            'codePostal' => $patient->getCodePostal(),
            'ville' => $patient->getVille(),
            'email' => $patient->getEmail(),
            'telephone' => $patient->getTelephone()
        ], JSON_UNESCAPED_SLASHES));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
