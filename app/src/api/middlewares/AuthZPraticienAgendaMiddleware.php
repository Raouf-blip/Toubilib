<?php
namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthZPraticienAgendaMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = $request->getAttribute('user');
        
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

        if (!$user || $user['role'] !== 10) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Accès réservé aux praticiens']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        if (!$praticienId) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'ID praticien manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Vérifier que le praticien authentifié est le propriétaire de l'agenda
        if ($user['id'] !== $praticienId) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Accès non autorisé à cet agenda']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
