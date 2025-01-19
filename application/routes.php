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
        ],
        '/users/:id' => [
            'controller'    => 'APP\UserController',
            'action'        => 'show',
            'dependencies'  => [UserService::class]
        ],
        '/users/:id/edit' => [
            'controller'    => 'APP\UserController',
            'action'        => 'edit',
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