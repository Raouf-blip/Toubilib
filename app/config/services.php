<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    // une factory pour instancier l'interface PraticienRepositoryInterface
    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        // qui injecte la connexion PDO
        return new PDOPraticienRepository($c->get('pdo.praticien'));
    },

    // une factory pour instancier l'interface ServicePraticienInterface
    ServicePraticienInterface::class => function (ContainerInterface $c) {
        // qui injecte le repository PraticienRepository
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
];