<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubilib\core\application\usecases\ServiceAuthInterface;
use toubilib\core\application\services\JWTService;
use Slim\Psr7\Response as SlimResponse;

class AuthLoginAction
{
    private ServiceAuthInterface $serviceAuth;
    private JWTService $jwtService;

    public function __construct(ServiceAuthInterface $serviceAuth, JWTService $jwtService)
    {
        $this->serviceAuth = $serviceAuth;
        $this->jwtService = $jwtService;
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


            // Déterminer le nom du rôle
            if ($auth->role === 1) {
                $nomRole = 'Patient';
            } elseif ($auth->role === 10) {
                $nomRole = 'Praticien';
            } else {
                $nomRole = 'Inconnu';
            }

            // Générer le token JWT
            $tokenPayload = [
                'id' => $auth->id,
                'email' => $auth->email,
                'role' => $auth->role
            ];
            
            $token = $this->jwtService->generateToken($tokenPayload);

            $out = [
                'token' => $token,
                'user' => [
                    'id' => $auth->id,
                    'email' => $auth->email,
                    'role' => $auth->role . ' - ' . $nomRole
                ],
                'expires_in' => 3600 // 1 heure en secondes
            ];

            $res = new SlimResponse();
            $res->getBody()->write(json_encode($out, JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $status = 500; // Erreur serveur par défaut
            $msg = "Erreur interne du serveur";
            
            // Seulement pour les erreurs de validation métier spécifiques
            if (strpos($e->getMessage(), 'inexistant') !== false) {
                $status = 404;
                $msg = $e->getMessage();
            } elseif (strpos($e->getMessage(), 'invalide') !== false) {
                $status = 400;
                $msg = $e->getMessage();
            }

            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['error' => $msg], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus($status);
        }
    }
}
