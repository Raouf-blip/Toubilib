<?php
namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use toubilib\core\application\usecases\ServiceRDVInterface;

class AuthZPraticienRDVMiddleware implements MiddlewareInterface
{
    private ServiceRDVInterface $serviceRDV;

    public function __construct(ServiceRDVInterface $serviceRDV)
    {
        $this->serviceRDV = $serviceRDV;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = $request->getAttribute('user');
        
        if (!$user || $user['role'] !== 10) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Accès réservé aux praticiens']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        $path = $request->getUri()->getPath();
        $pathParts = explode('/', trim($path, '/'));
        $rdvId = null;
        
        for ($i = 0; $i < count($pathParts) - 1; $i++) {
            if ($pathParts[$i] === 'rdvs' && isset($pathParts[$i + 1])) {
                $rdvId = $pathParts[$i + 1];
                break;
            }
        }

        if (!$rdvId) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'ID du RDV manquant']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $rdv = $this->serviceRDV->consulterRdv($rdvId);
            if (!$rdv) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => 'RDV non trouvé']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            if ($user['id'] !== $rdv->praticienId) {
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write(json_encode(['error' => 'Accès non autorisé à ce RDV']));
                return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
            }

            return $handler->handle($request);
        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Erreur lors de la vérification des permissions']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}

