<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'lock' => 'flock'
    ];

    $container->extension('framework', $configuration);
};
