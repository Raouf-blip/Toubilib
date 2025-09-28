<?php

use Psr\Container\ContainerInterface;
use toubilib\core\application\ports\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;
use toubilib\core\application\ports\RDVRepositoryInterface;
use toubilib\infra\repositories\PDORDVRepository;
use toubilib\core\application\usecases\ServiceRDV;
use toubilib\core\application\usecases\ServiceRDVInterface;
use toubilib\core\application\ports\PatientRepositoryInterface;
use toubilib\infra\repositories\PDOPatientRepository;
use toubilib\core\application\usecases\ServicePatient;

return [

    // connexions PDO
    'pdo.praticien' => fn() => new PDO(
        sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $_ENV['prat.host'],
            $_ENV['prat.port'] ?? 5432,
            $_ENV['prat.database']
        ),
        $_ENV['prat.username'],
        $_ENV['prat.password']
    ),

    'pdo.patient' => fn() => new PDO(
        sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $_ENV['pat.host'],
            $_ENV['pat.port'] ?? 5432,
            $_ENV['pat.database']
        ),
        $_ENV['pat.username'],
        $_ENV['pat.password']
    ),

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

    // repositories
    PraticienRepositoryInterface::class =>
        fn(ContainerInterface $c) => new PDOPraticienRepository($c->get('pdo.praticien')),

    PatientRepositoryInterface::class =>
        fn(ContainerInterface $c) => new PDOPatientRepository($c->get('pdo.patient')),

    RDVRepositoryInterface::class =>
        fn(ContainerInterface $c) => new PDORDVRepository($c->get('pdo.rdv')),

    // services
    ServicePraticienInterface::class =>
        fn(ContainerInterface $c) => new ServicePraticien($c->get(PraticienRepositoryInterface::class)),

    ServicePatient::class =>
        fn(ContainerInterface $c) => new ServicePatient($c->get(PatientRepositoryInterface::class)),

    ServiceRDVInterface::class =>
        fn(ContainerInterface $c) => new ServiceRDV(
            $c->get(RDVRepositoryInterface::class),
            $c->get(ServicePraticienInterface::class),
            $c->get(ServicePatient::class)
        ),

    // pour éviter d'injecter direct l'implémentation
    ServiceRDV::class => fn(ContainerInterface $c) => $c->get(ServiceRDVInterface::class),
];
