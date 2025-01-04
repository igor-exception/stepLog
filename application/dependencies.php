<?php

$dependencies = [
    'APP\UserController' => [
        'APP\UserService'
    ],
    'APP\UserService' => [
        'APP\UserRepository'
    ],
    'APP\UserRepository' => [
        '\PDO'
    ]
];