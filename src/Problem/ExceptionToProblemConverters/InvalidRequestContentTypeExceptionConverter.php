<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestContentTypeException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

/**
 * @deprecated
 * @psalm-suppress DeprecatedClass
 */
class InvalidRequestContentTypeExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof InvalidRequestContentTypeException) {
            return null;
        }

        $result = new Problem(
            'Invalid request content type',
            Response::HTTP_BAD_REQUEST,
            'invalid_request_content_type'
        );

        if ($exception->getCode() === InvalidRequestContentTypeException::CODE_MISSING) {
            $result->addDetails('code', 'missing');
        } elseif ($exception->getCode() === InvalidRequestContentTypeException::CODE_UNSUPPORTED) {
            $result->addDetails('code', 'unsupported');
        }

        if ($validContentTypes = $exception->getValidContentTypes()) {
            $result->addDetails('valid_content_types', $validContentTypes);
        }

        return $result;
    }
}
