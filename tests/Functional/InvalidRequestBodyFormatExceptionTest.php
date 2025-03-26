<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class InvalidRequestBodyFormatExceptionTest extends FunctionalTestCase
{
    public function testInvalidRequestBodyFormatException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => '1', 'field2' => 'val2', 'field3' => 'val3', 'field33' => '111'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/api/process-request', [], [], ['CONTENT_TYPE' => 'application/json'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format',
            'Invalid request body format'
        );
    }
}
