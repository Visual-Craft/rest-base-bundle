<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'framework' => [
            'rate_limiter' => [
                'username_ip_login' => [
                    'policy' => 'token_bucket',
                    'limit' => 2,
                    'rate' => ['interval' => '5 minutes'],
                ],
                'ip_login' => [
                    'policy' => 'sliding_window',
                    'limit' => 6,
                    'interval' => '15 minutes',
                ],
            ],
        ],
        'services' => [
            'app.login_rate_limiter' => [
                'class' => 'Symfony\Component\Security\Http\RateLimiter\DefaultLoginRateLimiter',
                'arguments' => [
                    '$globalFactory' => '@limiter.ip_loginip_login',
                    '$localFactory' => '@limiter.username_ip_login',
                    '$secret' => '%kernel.secret%',
                ],
            ],
        ],
    ];

    $container->extension('framework', $configuration['framework']);
    $container->extension('services', $configuration['services']);
};
