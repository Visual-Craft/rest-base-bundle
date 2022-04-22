<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class AuthenticationExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof AuthenticationException) {
            return null;
        }

        return new Problem(
            'Authentication error: ' . $exception->getMessageKey(),
            Response::HTTP_UNAUTHORIZED,
            'authentication_error'
        );
    }

    public static function getDefaultPriority(): int
    {
        return -50;
    }
}
