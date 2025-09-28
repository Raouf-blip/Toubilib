<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceRDVInterface;
use DateTime;
use Exception;

class ListRDVOccupesAction
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $praticienId = $args['id'] ?? null; // ID du praticien dans l'URL
        $query = $request->getQueryParams();
        $dateDebut = $query['dateDebut'] ?? null;
        $dateFin = $query['dateFin'] ?? null;

        if (!$praticienId || !$dateDebut || !$dateFin) {
            $response->getBody()->write(json_encode(['error' => 'Paramètres manquants']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $debut = new DateTime($dateDebut);
            $fin = new DateTime($dateFin);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Format de date invalide']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $creneaux = $this->serviceRDV->listerCreneauxOccupes($praticienId, $debut, $fin);

        $result = array_map(fn($rdv) => [
            'dateHeureDebut' => $rdv->dateHeureDebut,
            'dateHeureFin' => $rdv->dateHeureFin,
            'motifVisite' => $rdv->motifVisite
        ], $creneaux);

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
