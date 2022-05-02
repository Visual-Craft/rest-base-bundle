<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class AccessDeniedHttpExceptionTest extends FunctionalTestCase
{
    public function testAccessDeniedHttpException(): void
    {
        $client = static::createClient();
        $encodedData = json_encode(['field1' => 'val1', 'field2' => 'val2', 'field3' => 'val3']);
        $client->request('GET', '/api/access-denied-request', [], [], ['CONTENT_TYPE' => 'application/json'], $encodedData);

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_FORBIDDEN,
            'access_denied_http',
            'Invalid request'
        );
    }
}
