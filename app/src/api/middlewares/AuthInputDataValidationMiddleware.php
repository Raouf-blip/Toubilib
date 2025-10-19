<?php

namespace toubilib\api\middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;
use toubilib\core\application\dto\InputAuthDTO;

class AuthInputDataValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $data = (array)$request->getParsedBody();

        $errors = [];

        $required = ['email', 'mdp'];
        foreach ($required as $k) {
            if (!isset($data[$k]) || $data[$k] === '') {
                $errors[] = "Champ requis manquant: $k";
            }
        }

        if (!empty($errors)) {
            $res = new SlimResponse();
            $res->getBody()->write(json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format d'email invalide";
        }

        $dto = new InputAuthDTO(
            (string)$data['email'],
            (string)$data['mdp']
        );

        $request = $request->withAttribute('inputAuthDto', $dto);

        return $handler->handle($request);
    }
}
