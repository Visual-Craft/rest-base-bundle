<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'zone' => [
            [
                'path' => '^/api/',
            ],
        ],
    ];

    $container->extension('visual_craft_rest_base', $configuration);
};
