<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverters;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\Problem;

class TooManyLoginAttemptsAuthenticationExceptionConverter implements ExceptionToProblemConverterInterface
{
    #[\Override]
    public function convert(\Throwable $exception): ?Problem
    {
        if (!$exception instanceof TooManyLoginAttemptsAuthenticationException) {
            return null;
        }

        $problem = new Problem(
            'Login rate limit exceeded',
            Response::HTTP_TOO_MANY_REQUESTS,
            'too_many_login_attempts'
        );
        /**
         * @psalm-var string $retryAfter
         */
        $retryAfter = $exception->getMessageData()['%minutes%'] ?? '';
        $headers = [
            'X-RateLimit-Retry-After' => $retryAfter,
        ];
        $problem
            ->setHeaders($headers)
        ;

        return $problem;
    }

    public static function getDefaultPriority(): int
    {
        return 100;
    }
}
