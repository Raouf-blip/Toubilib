<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\AgendaPraticienAction;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\api\actions\RecherchePraticiensAction;
use toubilib\api\actions\RecherchePraticiensSpeVilleAction;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\api\actions\ListRDVOccupesAction;
use toubilib\core\application\usecases\ServiceRDVInterface;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\api\actions\GetRDVAction;
use toubilib\api\actions\CreateRDVAction;
use toubilib\api\actions\GetPatientAction;
use toubilib\core\application\ports\RDVRepositoryInterface;
use toubilib\infra\repositories\PDORDVRepository;
use toubilib\core\application\usecases\ServicePatient;
use toubilib\core\application\usecases\ServicePatientInterface;
use toubilib\core\application\services\HATEOASService;

return [

    // Définition de PDO pour RDV
    'pdo.rdv' => fn() => new PDO(
        sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $_ENV['rdv.host'],
            $_ENV['rdv.port'] ?? 5432,
            $_ENV['rdv.database']
        ),
        $_ENV['rdv.username'],
        $_ENV['rdv.password']
    ),

    // Repository RDV
    RDVRepositoryInterface::class => fn(ContainerInterface $c) =>
        new PDORDVRepository($c->get('pdo.rdv')),

    // Service RDV
    ServiceRDVInterface::class => function(ContainerInterface $c) {
        return new ServiceRDV(
            $c->get(RDVRepositoryInterface::class),
            $c->get(ServicePraticienInterface::class),
            $c->get(ServicePatientInterface::class)
        );
    },

    // Actions RDV
    ListRDVOccupesAction::class => fn(ContainerInterface $c) =>
        new ListRDVOccupesAction($c->get(ServiceRDVInterface::class)),

    GetRDVAction::class => fn(ContainerInterface $c) =>
        new GetRDVAction($c->get(ServiceRDVInterface::class), $c->get(HATEOASService::class)),

    CreateRDVAction::class => fn(ContainerInterface $c) =>
        new CreateRDVAction($c->get(ServiceRDVInterface::class)),

    // Actions praticiens
    ListPraticiensAction::class => fn(ContainerInterface $c) =>
        new ListPraticiensAction($c->get(ServicePraticienInterface::class), $c->get(HATEOASService::class)),

    RecherchePraticiensAction::class => fn(ContainerInterface $c) =>
        new RecherchePraticiensAction($c->get(ServicePraticienInterface::class), $c->get(HATEOASService::class)),
    
    RecherchePraticiensSpeVilleAction::class => fn(ContainerInterface $c) =>
        new RecherchePraticiensSpeVilleAction($c->get(ServicePraticienInterface::class), $c->get(HATEOASService::class)),

    AgendaPraticienAction::class => fn(ContainerInterface $c) =>
        new AgendaPraticienAction($c->get(ServiceRDVInterface::class)),

    // Actions patients
    GetPatientAction::class => fn(ContainerInterface $c) =>
        new GetPatientAction($c->get(ServicePatientInterface::class)),
];
