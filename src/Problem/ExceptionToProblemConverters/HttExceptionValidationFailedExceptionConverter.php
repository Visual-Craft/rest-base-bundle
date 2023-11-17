<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class HttExceptionValidationFailedExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        $previousException = $exception->getPrevious();

        if (
            !$exception instanceof HttpException
            || !$previousException instanceof ValidationFailedException
        ) {
            return null;
        }

        $problem = new Problem(
            'Validation error',
            Response::HTTP_BAD_REQUEST,
            'validation_error'
        );
        $problem->addDetails('violations', $previousException->getViolations());

        return $problem;
    }

    public static function getDefaultPriority(): int
    {
        return 0;
    }
}
