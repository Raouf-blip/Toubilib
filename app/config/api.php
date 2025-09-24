<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\api\actions\ListRDVOccupesAction;
use toubilib\core\application\usecases\ServiceRDV;

return [
    // une factory pour instancier la classe ListerPraticiensAction
    ListPraticiensAction::class => function (ContainerInterface $c) {
        return new ListPraticiensAction($c->get(ServicePraticienInterface::class));
    },
    ListRDVOccupesAction::class => fn($c) =>
    new ListRDVOccupesAction($c->get(ServiceRDV::class))
];