<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class ValidationErrorExceptionTest extends FunctionalTestCase
{
    public function testValidationErrorException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => '', 'field2' => 'val2', 'field3' => 'val3'], JSON_THROW_ON_ERROR);
        $client->request('POST', '/api/process-request', [], [], ['CONTENT_TYPE' => 'application/json'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }
}
