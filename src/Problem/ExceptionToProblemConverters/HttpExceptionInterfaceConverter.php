<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class HttpExceptionInterfaceConverter implements ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof HttpExceptionInterface) {
            return null;
        }

        $result = new Problem(
            'HTTP error: ' . (Response::$statusTexts[$exception->getStatusCode()] ?? 'unknown'),
            $exception->getStatusCode(),
            'http_error'
        );
        $result->setHeaders($exception->getHeaders());

        return $result;
    }

    public static function getDefaultPriority(): int
    {
        return -50;
    }
}
