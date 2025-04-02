<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class InvalidRequestContentTypeExceptionTest extends FunctionalTestCase
{
    public function testInvalidRequestContentTypeException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => '1', 'field2' => 'val2', 'field3' => 'val3'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/api/process-request', [], [], ['CONTENT_TYPE' => 'text'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'invalid_request_content_type',
            'Invalid request content type'
        );
    }
}
