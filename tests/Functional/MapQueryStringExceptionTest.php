<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

/**
 * @internal
 */
class MapQueryStringExceptionTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        if (!class_exists(MapQueryString::class)) {
            $this->markTestSkipped('The MapQueryString attribute is not available. Symfony required 6.3>');
        }
    }

    public function testSuccess(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-query-string',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'status' => 'placed',
                'total' => 11,
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testValidationError(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-query-string',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'comment' => 'Lorem',
                'total' => '',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }

    public function testMalformedDataError(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-query-string',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'status' => 'placed',
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }

    public function testUnsupportedFormatException(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-query-string',
            [],
            [],
            ['CONTENT_TYPE' => 'text/html'],
            json_encode([
                'status' => 'placed',
                'total' => 11,
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }
}
