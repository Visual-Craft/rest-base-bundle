<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class AccessDeniedHttpExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof AccessDeniedHttpException) {
            return null;
        }

        return new Problem(
            'Access denied http error: ' . $exception->getMessageKey(),
            Response::HTTP_FORBIDDEN,
            'access_denied_http'
        );
    }
}
