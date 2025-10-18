<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\api\actions\RecherchePraticiensAction;
use toubilib\api\actions\ListRDVOccupesAction;
use toubilib\api\actions\HomeAction;
use toubilib\api\actions\CreateRDVAction;
use toubilib\api\middlewares\RDVInputDataValidationMiddleware;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\api\actions\GetPatientAction;



return function( \Slim\App $app):\Slim\App {



    $app->get('/', HomeAction::class)->setName('home');

    $app->get('/praticiens', ListPraticiensAction::class)->setName('list_praticiens');

    $app->get('/praticiens/{id}', RecherchePraticiensAction::class)->setName('recherche_praticien');

    $app->get('/praticiens/{id}/rdvs/occupes', ListRDVOccupesAction::class)->setName('list_rdv_occupes');

    $app->get('/rdvs/{id}', \toubilib\api\actions\GetRDVAction::class);

    $app->post('/rdvs', CreateRDVAction::class)
        ->add(RDVInputDataValidationMiddleware::class)
        ->setName('create_rdv');
    
    $app->delete('/rdvs/{id}', AnnulerRDVAction::class);
    $app->get('/praticiens/{id}/agenda', \toubilib\api\actions\AgendaPraticienAction::class);
    $app->get('/patients/{id}', GetPatientAction::class);

    $app->post('/auth/login', \toubilib\api\actions\AuthLoginAction::class);


    return $app;
};