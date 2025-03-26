<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class HttpExceptionTest extends FunctionalTestCase
{
    public function testHttpException(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/wrong');

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_NOT_FOUND,
            'http_error',
            'HTTP error: Not Found'
        );
    }
}
