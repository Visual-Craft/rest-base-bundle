<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class AccessDeniedHttpExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if ($exception instanceof AccessDeniedHttpException) {
            return new Problem(
                'Access Denied error: Forbidden',
                Response::HTTP_FORBIDDEN,
                'forbidden'
            );
        }

        return null;
    }
}
