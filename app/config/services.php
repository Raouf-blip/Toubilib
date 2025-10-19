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
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\core\application\ports\AuthRepositoryInterface;
use toubilib\core\application\usecases\ServicePatientInterface;
use toubilib\core\application\usecases\ServiceAuth;
use toubilib\core\application\usecases\ServiceAuthInterface;
use toubilib\infra\repositories\PDOAuthRepository;
use toubilib\api\actions\AuthLoginAction;
use toubilib\core\application\services\JWTService;
use toubilib\api\middlewares\AuthNMiddleware;
use toubilib\api\middlewares\AuthZPatientMiddleware;
use toubilib\api\middlewares\AuthZPraticienMiddleware;
use toubilib\api\middlewares\AuthZRDVMiddleware;
use toubilib\api\middlewares\AuthZPraticienAgendaMiddleware;
use toubilib\api\middlewares\CORSMiddleware;
use toubilib\core\application\services\HATEOASService;

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

    'pdo.auth' => fn() => new PDO(
        sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $_ENV['auth.host'],
            $_ENV['auth.port'] ?? 5432,
            $_ENV['auth.database']
        ),
        $_ENV['auth.username'],
        $_ENV['auth.password']
    ),

    // repositories
    PraticienRepositoryInterface::class =>
    fn(ContainerInterface $c) => new PDOPraticienRepository($c->get('pdo.praticien')),

    PatientRepositoryInterface::class =>
    fn(ContainerInterface $c) => new PDOPatientRepository($c->get('pdo.patient')),

    RDVRepositoryInterface::class =>
    fn(ContainerInterface $c) => new PDORDVRepository($c->get('pdo.rdv')),

    AuthRepositoryInterface::class =>
    fn(ContainerInterface $c) => new PDOAuthRepository($c->get('pdo.auth')),

    // services
    ServicePraticienInterface::class =>
    fn(ContainerInterface $c) => new ServicePraticien($c->get(PraticienRepositoryInterface::class)),

    ServicePatient::class =>
    fn(ContainerInterface $c) => new ServicePatient($c->get(PatientRepositoryInterface::class)),

    ServicePatientInterface::class =>
    fn(ContainerInterface $c) => new ServicePatient($c->get(PatientRepositoryInterface::class)),

    ServiceRDVInterface::class =>
    fn(ContainerInterface $c) => new ServiceRDV(
        $c->get(RDVRepositoryInterface::class),
        $c->get(ServicePraticienInterface::class),
        $c->get(ServicePatient::class)
    ),

    ServiceAuthInterface::class =>
    fn(ContainerInterface $c) => new ServiceAuth(
        $c->get(AuthRepositoryInterface::class)
    ),

    JWTService::class => fn() => new JWTService(),

    AuthNMiddleware::class => fn(ContainerInterface $c) => new AuthNMiddleware($c->get(JWTService::class)),

    AuthZPatientMiddleware::class => fn() => new AuthZPatientMiddleware(),

    AuthZPraticienMiddleware::class => fn() => new AuthZPraticienMiddleware(),

    AuthZRDVMiddleware::class => fn(ContainerInterface $c) => new AuthZRDVMiddleware($c->get(ServiceRDVInterface::class)),

    AuthZPraticienAgendaMiddleware::class => fn() => new AuthZPraticienAgendaMiddleware(),

    CORSMiddleware::class => fn() => new CORSMiddleware(),

    HATEOASService::class => fn() => new HATEOASService(),

    // pour éviter d'injecter direct l'implémentation
    ServiceRDV::class => fn(ContainerInterface $c) => $c->get(ServiceRDVInterface::class),

    AnnulerRDVAction::class => function (ContainerInterface $c) {
        return new AnnulerRDVAction($c->get(ServiceRDVInterface::class));
    },

        AuthLoginAction::class =>
        fn(ContainerInterface $c) => new AuthLoginAction(
            $c->get(ServiceAuthInterface::class),
            $c->get(JWTService::class),
            $c->get(HATEOASService::class)
        )
];
