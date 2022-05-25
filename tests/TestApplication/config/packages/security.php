<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $container): void {
    $container->extension('security', [
        'enable_authenticator_manager' => true,
        'firewalls' => [
            'main' => [
                'entry_point' => \VisualCraft\RestBaseBundle\Security\AuthenticationEntryPoint::class,
                'lazy' => true,
                'provider' => 'users',
                'json_login' => [
                    'check_path' => '/api/login',
                    'username_path' => 'login',
                    'password_path' => 'password',
                    'failure_handler' => \VisualCraft\RestBaseBundle\Security\AuthenticationFailureHandler::class,
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
};
