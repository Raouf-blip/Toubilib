<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\core\application\ports\RDVRepositoryInterface;
use toubilib\infra\repositories\PDORDVRepository;
use toubilib\core\application\usecases\ServiceRDV;

return [
    'pdo.praticien' => function() {
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            $_ENV['prat.host'],
            $_ENV['prat.port'] ?? 5432,
            $_ENV['prat.database']
        );
        return new PDO($dsn, $_ENV['prat.username'], $_ENV['prat.password']);
    },

    PraticienRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPraticienRepository($c->get('pdo.praticien'));
    },

    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien($c->get(PraticienRepositoryInterface::class));
    },
    
    'pdo.rdv' => function() {
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            $_ENV['rdv.host'],
            $_ENV['rdv.port'] ?? 5432,
            $_ENV['rdv.database']
        );
        return new PDO($dsn, $_ENV['rdv.username'], $_ENV['rdv.password']);
    },

    RDVRepositoryInterface::class => function(ContainerInterface $c) {
        return new PDORDVRepository($c->get('pdo.rdv'));
    },

    ServiceRDV::class => function(ContainerInterface $c) {
        return new ServiceRDV($c->get(RDVRepositoryInterface::class));
    },
];