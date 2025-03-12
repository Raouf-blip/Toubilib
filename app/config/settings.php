<?php

use Psr\Container\ContainerInterface;
use jira\application\actions\GetAllUserStoriesAction;
use jira\domain\ports\driven\UserStoryRepository;
use jira\domain\ports\driving\UserStoryServiceInterface;
use jira\domain\usecases\UserStoryService;
use jira\infra\PgUserStoryRepository;

return [

    // settings
    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'jira.db.config' => __DIR__ . '/jira.db.ini',
    
    // application
    GetAllUserStoriesAction::class=> function (ContainerInterface $c) {
        return new GetAllUserStoriesAction($c->get(UserStoryServiceInterface::class));
    },

    // service
    UserStoryServiceInterface::class => function (ContainerInterface $c) {
        return new UserStoryService($c->get(UserStoryRepository::class));
    },

    // infra
     'jira.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('jira.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    UserStoryRepository::class => fn(ContainerInterface $c) => new PgUserStoryRepository($c->get('jira.pdo')),
    
];

