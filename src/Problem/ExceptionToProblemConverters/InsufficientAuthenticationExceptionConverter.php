<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class InsufficientAuthenticationExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof InsufficientAuthenticationException) {
            return null;
        }

        return new Problem(
            'Insufficient authentication error: ' . $exception->getMessageKey(),
            Response::HTTP_UNAUTHORIZED,
            'insufficient_authentication_error'
        );
    }
}
