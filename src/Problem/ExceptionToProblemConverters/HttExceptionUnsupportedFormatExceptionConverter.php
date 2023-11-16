<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class HttExceptionUnsupportedFormatExceptionConverter implements ExceptionToProblemConverterInterface
{
    /** @psalm-suppress MixedInferredReturnType */
    public function convert(\Throwable $exception): ?Problem
    {
        $previousException = $exception->getPrevious();

        /** @psalm-suppress UndefinedClass */
        if (!$previousException instanceof UnsupportedFormatException) {
            return null;
        }

        $result = new Problem(
            'Validation error',
            Response::HTTP_BAD_REQUEST,
            'validation_error'
        );
        $result->addDetails('cause', 'unsupported_format_exception');

        return $result;
    }

    public static function getDefaultPriority(): int
    {
        return 0;
    }
}
