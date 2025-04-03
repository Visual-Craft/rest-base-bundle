<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'rate_limiter' => [
            'username_ip_login' => [
                'policy' => 'token_bucket',
                'limit' => 2,
                'rate' => ['interval' => '5 minutes'],
            ],
            'ip_login' => [
                'policy' => 'sliding_window',
                'limit' => 2,
                'interval' => '15 minutes',
            ],
        ],
    ];

    $container->extension('framework', $configuration);
};
