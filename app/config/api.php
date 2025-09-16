<?php

use Psr\Container\ContainerInterface;
use toubilib\api\actions\ListPraticiensAction;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    // une factory pour instancier la classe ListerPraticiensAction
    ListPraticiensAction::class => function (ContainerInterface $c) {
        return new ListPraticiensAction($c->get(ServicePraticienInterface::class));
    },
];