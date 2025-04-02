<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class TooManyLoginAttemptsAuthenticationExceptionTest extends FunctionalTestCase
{
    public function testAuthenticationException(): void
    {
        $client = static::createClient();

        $testCases = [
            'invalid1' => [
                'login' => 'user1',
                'password' => 'incorrect_password',
            ],
            'invalid2' => [
                'login' => 'user1',
                'password' => 'incorrect_password2',
            ],
            'valid' => [
                'login' => 'user1',
                'password' => 'correct_password',
            ],
        ];

        foreach ($testCases as $key => $testCase) {
            $json = json_encode([
                'login' => $testCase['login'],
                'password' => $testCase['password'],
                JSON_THROW_ON_ERROR,
            ]);
            $this->assertIsString($json);
            $client->request(
                'POST',
                '/api/login',
                [],
                [],
                ['CONTENT_TYPE' => 'application/json'],
                $json
            );

            if ($key === 'valid') {
                $this->assertProblemResponse(
                    $client->getResponse(),
                    Response::HTTP_TOO_MANY_REQUESTS,
                    'too_many_login_attempts',
                    'Login rate limit exceeded'
                );
            } else {
                $this->assertProblemResponse(
                    $client->getResponse(),
                    Response::HTTP_UNAUTHORIZED,
                    'authentication_error',
                    'Authentication error: Invalid credentials.'
                );
            }
        }
    }
}
