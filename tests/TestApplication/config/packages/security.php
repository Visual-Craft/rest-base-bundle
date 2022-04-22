<?php

declare(strict_types=1);

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

$container->loadFromExtension('security', [
    'enable_authenticator_manager' => true,
    'firewalls' => [
        'main' => [
            'entry_point' => 'VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint',
            'lazy' => true,
            'provider' => 'users',
            'json_login' => [
                'check_path' => '/api/login',
                'username_path' => 'login',
                'password_path' => 'password',
                'failure_handler' => 'VisualCraft\RestBaseBundle\Tests\TestApplication\Security\AuthenticationFailureHandler',
            ],
        ],
    ],
    'password_hashers' => [
        PasswordAuthenticatedUserInterface::class => [
            'algorithm' => 'plaintext',
        ],
    ],
    'providers' => [
        'users' => [
            'memory' => [
                'users' => [
                    'user1' => [
                        'password' => 'correct_password',
                        'roles' => ['ROLE_ADMIN'],
                    ],
                ],
            ],
        ],
    ],
    'access_control' => [
        [
            'path' => '^/api/authentication-required',
            'roles' => ['ROLE_ADMIN'],
        ],
    ],
]);
