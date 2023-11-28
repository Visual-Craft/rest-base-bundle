<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class HttExceptionValidationFailedExceptionSerializerConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof UnexpectedValueException && !$exception instanceof ExtraAttributesException) {
            return null;
        }

        $problem = new Problem(
            'Invalid request body format',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format'
        );

        $cause = 'invalid_format';

        if ($exception instanceof UnexpectedValueException) {
            $cause = 'unexpected_value';
        }

        if ($exception instanceof ExtraAttributesException) {
            $cause = 'extra_attributes';
        }

        $problem->addDetails('cause', $cause);

        return $problem;
    }

    public static function getDefaultPriority(): int
    {
        return 0;
    }
}
