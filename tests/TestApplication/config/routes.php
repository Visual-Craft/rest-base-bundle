<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\LoginController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\MapQueryParameterController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\MapQueryStringController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\MapRequestPayloadController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\ProcessRequestController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\ThrowAccessDeniedHttpExceptionController;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Controller\ThrowInvalidRequestExceptionController;

return static function (RoutingConfigurator $routes): void {
    $routes
        ->add('access_denied_request', '/api/access-denied-request')
        ->controller(ThrowAccessDeniedHttpExceptionController::class)
    ;
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
    /** @psalm-suppress UndefinedClass, MixedArgument */
    $routes
        ->add('map_request_payload', '/api/map-request-payload')
        ->controller(MapRequestPayloadController::class)
    ;
    /** @psalm-suppress UndefinedClass, MixedArgument */
    $routes
        ->add('map_query_string', '/api/map-query-string')
        ->controller(MapQueryStringController::class)
    ;
    /** @psalm-suppress UndefinedClass, MixedArgument */
    $routes
        ->add('map_query_parameter', '/api/map-query-parameter')
        ->controller(MapQueryParameterController::class)
    ;
};
