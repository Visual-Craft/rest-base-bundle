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
        ];

        foreach ($testCases as $testCase) {
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

            $this->assertProblemResponse(
                $client->getResponse(),
                Response::HTTP_UNAUTHORIZED,
                'authentication_error',
                'Authentication error: Invalid credentials.'
            );
        }

        $json = json_encode([
            'login' => 'user1',
            'password' => 'correct_password',
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

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_TOO_MANY_REQUESTS,
            'too_many_login_attempts',
            'Login rate limit exceeded'
        );
    }
}
