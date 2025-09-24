<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\api\actions\ListRDVOccupesAction;
use toubilib\api\actions\HomeAction;


return function( \Slim\App $app):\Slim\App {



    // $app->get('/', HomeAction::class)->setName('home');

    $app->get('/praticiens', ListPraticiensAction::class)->setName('list_praticiens');

    $app->get('/praticiens/{id}/rdvs/occupes', ListRDVOccupesAction::class)->setName('list_rdv_occupes');


    return $app;
};