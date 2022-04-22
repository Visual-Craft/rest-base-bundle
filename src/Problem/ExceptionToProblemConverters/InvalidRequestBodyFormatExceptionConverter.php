<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestBodyFormatException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class InvalidRequestBodyFormatExceptionConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof InvalidRequestBodyFormatException) {
            return null;
        }

        $result = new Problem(
            'Invalid request body format',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format'
        );
        $cause = 'invalid_format';

        if (($previousException = $exception->getPrevious()) !== null) {
            if ($previousException instanceof UnexpectedValueException) {
                $cause = 'unexpected_value';
            } elseif ($previousException instanceof ExtraAttributesException) {
                $cause = 'extra_attributes';
            }
        }

        $result->addDetails('cause', $cause);

        return $result;
    }
}
