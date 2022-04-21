<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\LoginController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\ProcessRequestController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\ThrowInvalidRequestExceptionController;

return function (RoutingConfigurator $routes): void {
    $routes
        ->add('invalid_request', '/api/invalid-request')
        ->controller(ThrowInvalidRequestExceptionController::class)
    ;
    $routes
        ->add('process_request', '/api/process-request')
        ->controller(ProcessRequestController::class)
    ;
    $routes
        ->add('authentication_required', '/api/authentication-required')
        ->controller(ProcessRequestController::class)
    ;
    $routes
        ->add('login', '/api/login')
        ->controller(LoginController::class)
    ;
};
