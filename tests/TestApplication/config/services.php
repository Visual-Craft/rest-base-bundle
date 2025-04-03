<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Psr\Log\NullLogger;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;

    $services->load('VisualCraft\\RestBaseBundle\\Tests\\TestApplication\\', '../src/*')
        ->exclude('../Kernel.php')
    ;

    $container->services()->set('logger', NullLogger::class);
    $container->services()->set('app.login_rate_limiter', 'Symfony\Component\Security\Http\RateLimiter\DefaultLoginRateLimiter')
        ->args([
            '$globalFactory' => service('limiter.ip_login'),
            '$localFactory' => service('limiter.username_ip_login'),
            '$secret' => 'secret',
        ])
    ;
};
