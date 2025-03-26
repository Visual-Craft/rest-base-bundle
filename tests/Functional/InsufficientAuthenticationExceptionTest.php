<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class InsufficientAuthenticationExceptionTest extends FunctionalTestCase
{
    public function testInsufficientAuthenticationException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => '1', 'field2' => 'val2', 'field3' => 'val3'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/api/authentication-required', [], [], ['CONTENT_TYPE' => 'application/json'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_UNAUTHORIZED,
            'insufficient_authentication_error',
            'Insufficient authentication error: Not privileged to request the resource.'
        );
    }
}
