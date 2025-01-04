<?php
use APP\UserService;
use APP\UserController;
use APP\UserRepository;

$routes = [
    'GET' => [
        '/' => [
            'controller'    => 'APP\InitController',
            'action'        => 'index',
            'dependencies'  => []
        ],
        '/user/create' => [
            'controller'    => 'APP\UserController',
            'action'        => 'create',
            'dependencies'  => [UserService::class]
        ],
        '/users' => [
            'controller'    => 'APP\UserController',
            'action'        => 'index',
            'dependencies'  => [UserService::class]
        ]
    ],
    'POST' => [
        '/user/create' => [
            'controller'    => 'APP\UserController',
            'action'        => 'store',
            'dependencies'  => [UserService::class]
        ]
    ]
];


function resolveDependencies(string $dependencyName): object
{
    if($dependencyName == UserController::class) {
        $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
        $repository = new UserRepository($pdo);
        $service = new UserService($repository);
        return new UserController($service);
    }elseif($dependencyName == UserService::class) {
        $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
        $repository = new UserRepository($pdo);
        return new UserService($repository);
    }elseif($dependencyName == UserRepository::class) {
        $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
        return new UserRepository($pdo);
    }
}