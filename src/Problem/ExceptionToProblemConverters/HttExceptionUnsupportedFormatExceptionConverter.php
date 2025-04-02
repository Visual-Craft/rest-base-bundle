<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class HttExceptionUnsupportedFormatExceptionConverter implements ExceptionToProblemConverterInterface
{
    /** @psalm-suppress MixedInferredReturnType */
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        $previousException = $exception->getPrevious();

        /** @psalm-suppress UndefinedClass */
        if (
            !$exception instanceof HttpException
            || !$previousException instanceof UnsupportedFormatException
        ) {
            return null;
        }

        return new Problem(
            'Invalid request content type',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_content_type'
        );
    }

    public static function getDefaultPriority(): int
    {
        return 0;
    }
}
