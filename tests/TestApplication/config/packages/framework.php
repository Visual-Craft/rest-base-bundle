<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException;
use VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException;

return static function (ContainerConfigurator $container): void {
    $configuration = [
        'secret' => 'F00',
        'test' => true,
        'exceptions' => [
            InvalidRequestBodyFormatException::class => [
                'log_level' => 'error',
            ],
            InsufficientAuthenticationException::class => [
                'log_level' => 'error',
            ],
            InvalidRequestContentTypeException::class => [
                'log_level' => 'error',
            ],
            InvalidRequestException::class => [
                'log_level' => 'error',
            ],
            ValidationErrorException::class => [
                'log_level' => 'error',
            ],
        ],
        'session' => [
            'handler_id' => null,
            'cookie_secure' => 'auto',
            'cookie_samesite' => 'lax',
            'storage_factory_id' => 'session.storage.factory.mock_file',
        ],
        'serializer' =>[
            'default_context' => [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ],
        ],
    ];

    $container->extension('framework', $configuration);
};
