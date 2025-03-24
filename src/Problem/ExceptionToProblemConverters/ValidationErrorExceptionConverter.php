<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use VisualCraft\RestBaseBundle\Exceptions\ValidationErrorException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

/**
 * @deprecated
 */
class ValidationErrorExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof ValidationErrorException) {
            return null;
        }

        $result = new Problem(
            'Validation error',
            Response::HTTP_BAD_REQUEST,
            'validation_error'
        );
        $result->addDetails('violations', $exception->getViolationList());

        return $result;
    }
}
