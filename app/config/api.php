<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\api\actions\RecherchePraticiensAction;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\api\actions\ListRDVOccupesAction;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\api\actions\GetRDVAction;

return [
    // une factory pour instancier la classe ListerPraticiensAction
    ListPraticiensAction::class => function (ContainerInterface $c) {
        return new ListPraticiensAction($c->get(ServicePraticienInterface::class));
    },

    // Consulter un praticien par ID
    RecherchePraticiensAction::class => function (ContainerInterface $c) {
        return new RecherchePraticiensAction($c->get(ServicePraticienInterface::class));
    },

    // liste des rdv occupés
    ListRDVOccupesAction::class => fn($c) =>
        new ListRDVOccupesAction($c->get(ServiceRDV::class)),

    // Consulter un RDV par ID
    GetRDVAction::class => fn($c) =>
        new GetRDVAction($c->get(ServiceRDV::class)),
];