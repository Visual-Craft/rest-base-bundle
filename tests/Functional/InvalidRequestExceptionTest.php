<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class InvalidRequestExceptionTest extends FunctionalTestCase
{
    public function testInvalidRequestException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => 'val1', 'field2' => 'val2', 'field3' => 'val3'], JSON_THROW_ON_ERROR);
        $client->request('GET', '/api/invalid-request', [], [], ['CONTENT_TYPE' => 'application/json'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'invalid_request',
            'Invalid request'
        );
    }
}
