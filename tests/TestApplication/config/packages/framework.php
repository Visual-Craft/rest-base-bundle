<?php

declare(strict_types=1);

$configuration = [
    'secret' => 'F00',
    'test' => true,
    'exceptions' => [
        'VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException' => [
            'log_level' => 'error',
        ],
        'Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException' => [
            'log_level' => 'error',
        ],
        'VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException' => [
            'log_level' => 'error',
        ],
        'VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException' => [
            'log_level' => 'error',
        ],
        'VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException' => [
            'log_level' => 'error',
        ],
    ],
];

$container->loadFromExtension('framework', $configuration);
