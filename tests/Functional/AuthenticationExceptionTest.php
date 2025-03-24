<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

/**
 * @internal
 */
class AuthenticationExceptionTest extends FunctionalTestCase
{
    public function testAuthenticationException(): void
    {
        $client = static::createClient();
        $json = json_encode([
            'login' => 'user1',
            'password' => 'incorrect_password',
            JSON_THROW_ON_ERROR
        ]);
        Assert::string($json);
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
}
