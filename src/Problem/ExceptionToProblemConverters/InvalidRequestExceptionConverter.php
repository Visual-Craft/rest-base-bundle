<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

/**
 * @deprecated
 * @psalm-suppress ClassMustBeFinal
 */
class InvalidRequestExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof InvalidRequestException) {
            return null;
        }

        return new Problem(
            'Invalid request',
            Response::HTTP_BAD_REQUEST,
            'invalid_request'
        );
    }

    public static function getDefaultPriority(): int
    {
        return -50;
    }
}
