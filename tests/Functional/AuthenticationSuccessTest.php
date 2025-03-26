<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class AuthenticationSuccessTest extends WebTestCase
{
    public function testAuthenticationSuccess(): void
    {
        $client = static::createClient();
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
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $content = $response->getContent();
        $this->assertIsString($content);
        $this->assertJsonStringEqualsJsonString('{"user": "user1"}', $content);
    }
}
