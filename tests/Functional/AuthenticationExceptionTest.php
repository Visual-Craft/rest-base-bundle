<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class AuthenticationExceptionTest extends FunctionalTestCase
{
    public function testAuthenticationException(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'login' => 'user1',
                'password' => 'incorrect_password',
            ])
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_UNAUTHORIZED,
            'authentication_error',
            'Authentication error: Invalid credentials.'
        );
    }
}
