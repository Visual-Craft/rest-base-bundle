<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

/**
 * @internal
 */
class MapRequestPayloadExceptionTest extends FunctionalTestCase
{
    public function testSuccess(): void
    {
        if (!class_exists(MapRequestPayload::class)) {
            $this->markTestSkipped();
        }

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-request-payload',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'comment' => 'Lorem Ipsum is simply dummy text of the printing.',
                'rating' => 1,
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testValidationError(): void
    {
        if (!class_exists(MapRequestPayload::class)) {
            $this->markTestSkipped();
        }

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-request-payload',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'comment' => 'Lorem',
                'rating' => 10,
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
        if (!class_exists(MapRequestPayload::class)) {
            $this->markTestSkipped();
        }

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-request-payload',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'comment' => 'Lorem Ipsum is simply dummy text of the printing.',
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
        if (!class_exists(MapRequestPayload::class)) {
            $this->markTestSkipped();
        }

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-request-payload',
            [],
            [],
            ['CONTENT_TYPE' => 'text/html'],
            json_encode([
                'comment' => 'Lorem Ipsum is simply dummy text of the printing.',
                'rating' => 1,
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