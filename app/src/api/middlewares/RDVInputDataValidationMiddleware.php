<?php
namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use toubilib\core\application\dto\InputRDVDTO;

class RDVInputDataValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = (array)$request->getParsedBody();

        $errors = [];

        $required = ['praticienId', 'patientId', 'dateHeureDebut', 'duree', 'motifVisite'];
        foreach ($required as $k) {
            if (!isset($data[$k]) || $data[$k] === '') {
                $errors[] = "Champ requis manquant: $k";
            }
        }

        if (isset($data['duree']) && !is_numeric($data['duree'])) {
            $errors[] = "duree doit être un entier (minutes)";
        } else {
            $duree = isset($data['duree']) ? (int)$data['duree'] : 0;
            if ($duree <= 0 || $duree > 24*60) $errors[] = "duree invalide";
        }

        if (isset($data['dateHeureDebut'])) {
            try {
                new \DateTime($data['dateHeureDebut']);
            } catch (\Exception $e) {
                $errors[] = "dateHeureDebut format invalide (ex: '2025-12-10 14:00:00')";
            }
        }

        if (!empty($errors)) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $dto = new InputRDVDTO(
            (string)$data['praticienId'],
            (string)$data['patientId'],
            isset($data['patientEmail']) ? (string)$data['patientEmail'] : null,
            (string)$data['dateHeureDebut'],
            (int)$data['duree'],
            (string)$data['motifVisite']
        );

        $request = $request->withAttribute('inputRdvDto', $dto);

        return $handler->handle($request);
    }
}
