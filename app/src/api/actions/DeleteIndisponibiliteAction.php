<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceIndisponibiliteInterface;
use toubilib\core\application\services\HATEOASService;

class DeleteIndisponibiliteAction
{
    private ServiceIndisponibiliteInterface $serviceIndisponibilite;
    private HATEOASService $hateoasService;

    public function __construct(
        ServiceIndisponibiliteInterface $serviceIndisponibilite,
        HATEOASService $hateoasService
    ) {
        $this->serviceIndisponibilite = $serviceIndisponibilite;
        $this->hateoasService = $hateoasService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $indisponibiliteId = $args['indisponibiliteId'] ?? null;
        if (!$indisponibiliteId) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'ID indisponibilité manquant'
            ], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $this->serviceIndisponibilite->supprimerIndisponibilite($indisponibiliteId);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(204);
        } catch (\Exception $e) {
            $statusCode = 400;
            if (strpos($e->getMessage(), 'inexistant') !== false) {
                $statusCode = 404;
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($statusCode);
        }
    }
}

