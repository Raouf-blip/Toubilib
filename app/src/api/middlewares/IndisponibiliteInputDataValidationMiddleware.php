<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use toubilib\core\application\dto\InputIndisponibiliteDTO;

class IndisponibiliteInputDataValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = (array)$request->getParsedBody();
        
        // Extraire l'ID du praticien depuis l'URI
        $path = $request->getUri()->getPath();
        $pathParts = explode('/', trim($path, '/'));
        $praticienId = null;
        
        // Chercher l'ID après '/praticiens/'
        for ($i = 0; $i < count($pathParts) - 1; $i++) {
            if ($pathParts[$i] === 'praticiens' && isset($pathParts[$i + 1])) {
                $praticienId = $pathParts[$i + 1];
                break;
            }
        }

        $errors = [];

        // Champs requis
        $required = ['date_debut', 'date_fin'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $errors[] = "Le champ '$field' est requis";
            }
        }

        // Validation des formats de date
        if (isset($data['date_debut']) && $data['date_debut'] !== '') {
            $dateDebut = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date_debut']);
            if (!$dateDebut || $dateDebut->format('Y-m-d H:i:s') !== $data['date_debut']) {
                $errors[] = "La date de début doit être au format YYYY-MM-DD HH:MM:SS";
            }
        }

        if (isset($data['date_fin']) && $data['date_fin'] !== '') {
            $dateFin = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date_fin']);
            if (!$dateFin || $dateFin->format('Y-m-d H:i:s') !== $data['date_fin']) {
                $errors[] = "La date de fin doit être au format YYYY-MM-DD HH:MM:SS";
            }
        }

        // Vérifier que date_fin > date_debut
        if (isset($data['date_debut']) && isset($data['date_fin']) && 
            $data['date_debut'] !== '' && $data['date_fin'] !== '') {
            $dateDebut = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date_debut']);
            $dateFin = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date_fin']);
            if ($dateDebut && $dateFin && $dateFin <= $dateDebut) {
                $errors[] = "La date de fin doit être postérieure à la date de début";
            }
        }

        // Si erreurs, retourner 400
        if (!empty($errors)) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode([
                'status' => 'error',
                'errors' => $errors
            ], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Créer le DTO
        if (!$praticienId) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'ID praticien manquant'
            ], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $dto = new InputIndisponibiliteDTO(
            $praticienId,
            (string)$data['date_debut'],
            (string)$data['date_fin'],
            isset($data['raison']) && $data['raison'] !== '' ? (string)$data['raison'] : null
        );

        $request = $request->withAttribute('inputIndisponibiliteDto', $dto);

        return $handler->handle($request);
    }
}

