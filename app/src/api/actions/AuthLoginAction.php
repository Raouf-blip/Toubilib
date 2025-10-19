<?php
namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceAuthInterface;
use Slim\Psr7\Response as SlimResponse;

class AuthLoginAction
{
    private ServiceAuthInterface $serviceAuth;

    public function __construct(ServiceAuthInterface $serviceAuth)
    {
        $this->serviceAuth = $serviceAuth;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $dto = $request->getAttribute('inputAuthDto');
        if (! $dto) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['error' => 'Input DTO manquant (middleware absent?)']));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        try {
            $auth = $this->serviceAuth->authentifier($dto->email, $dto->mdp);

            if (!$auth) {
                $res = new SlimResponse();
                $res->getBody()->write(json_encode(['error' => 'Email ou mot de passe incorrect'], JSON_UNESCAPED_UNICODE));
                return $res->withHeader('Content-Type', 'application/json')->withStatus(401);
            }

            $out = [
                'id' => $auth->id,
                'email' => $auth->email,
                'role' => $auth->role
            ];

            $res = new SlimResponse();
            $res->getBody()->write(json_encode($out, JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $status = 400;
            $msg = $e->getMessage();

            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus($status);
        }
    }
}