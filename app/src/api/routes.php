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
use toubilib\api\middlewares\AuthInputDataValidationMiddleware;
use toubilib\api\middlewares\AuthNMiddleware;
use toubilib\api\middlewares\AuthZPatientMiddleware;
use toubilib\api\middlewares\AuthZPraticienMiddleware;
use toubilib\api\middlewares\AuthZRDVMiddleware;
use toubilib\api\middlewares\AuthZPraticienAgendaMiddleware;
use toubilib\api\middlewares\AuthZPraticienRDVMiddleware;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\api\actions\GetPatientAction;
use toubilib\api\actions\MarquerRDVHonoreAction;
use toubilib\api\actions\MarquerRDVNonHonoreAction;



return function( \Slim\App $app):\Slim\App {

    $app->get('/', HomeAction::class)->setName('home');

    $app->post('/auth/login', \toubilib\api\actions\AuthLoginAction::class)
        ->add(AuthInputDataValidationMiddleware::class)
        ->setName('auth_login');

    $app->get('/praticiens', ListPraticiensAction::class)->setName('list_praticiens');

    $app->get('/praticiens/{id}', RecherchePraticiensAction::class)->setName('recherche_praticien');

    $app->get('/praticiens/{id}/rdvs/occupes', ListRDVOccupesAction::class)->setName('list_rdv_occupes');

    // Opération 4: Consulter un RDV - praticien ou patient du RDV
    $app->get('/rdvs/{id}', \toubilib\api\actions\GetRDVAction::class)
        ->add(AuthZRDVMiddleware::class)
        ->add(AuthNMiddleware::class);

    // Opération 5: Réserver un RDV - patient uniquement
    $app->post('/rdvs', CreateRDVAction::class)
        ->add(RDVInputDataValidationMiddleware::class)
        ->add(AuthZPatientMiddleware::class)
        ->add(AuthNMiddleware::class)
        ->setName('create_rdv');
    
    // Opération 6: Annuler un RDV - praticien ou patient du RDV
    $app->delete('/rdvs/{id}', AnnulerRDVAction::class)
        ->add(AuthZRDVMiddleware::class)
        ->add(AuthNMiddleware::class);
    
    // Marquer un RDV comme honoré - praticien propriétaire uniquement
    $app->patch('/rdvs/{id}/honorer', MarquerRDVHonoreAction::class)
        ->add(AuthZPraticienRDVMiddleware::class)
        ->add(AuthNMiddleware::class)
        ->setName('marquer_rdv_honore');
    
    // Marquer un RDV comme non honoré - praticien propriétaire uniquement
    $app->patch('/rdvs/{id}/non-honorer', MarquerRDVNonHonoreAction::class)
        ->add(AuthZPraticienRDVMiddleware::class)
        ->add(AuthNMiddleware::class)
        ->setName('marquer_rdv_non_honore');
        
    // Opération 7: Afficher agenda praticien - praticien propriétaire
    $app->get('/praticiens/{id}/agenda', \toubilib\api\actions\AgendaPraticienAction::class)
        ->add(AuthZPraticienAgendaMiddleware::class)
        ->add(AuthNMiddleware::class);
    $app->get('/patients/{id}', GetPatientAction::class);

    return $app;
};