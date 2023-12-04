<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class UnexpectedValueExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof UnexpectedValueException) {
            return null;
        }

        $problem = new Problem(
            'Invalid request body format',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format'
        );

        $problem->addDetails('cause', 'unexpected_value');

        return $problem;
    }

    public static function getDefaultPriority(): int
    {
        return 0;
    }
}
