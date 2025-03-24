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
    #[\Override]
    protected function setUp(): void
    {
        if (!class_exists(MapRequestPayload::class)) {
            $this->markTestSkipped('The MapRequestPayload attribute is not available. Symfony required 6.3>');
        }
    }

    public function testSuccess(): void
    {
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

    /**
     * @dataProvider provideValidationErrorCases
     */
    public function testValidationError(array $content): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/map-request-payload',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($content, JSON_THROW_ON_ERROR)
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }

    /**
      @psalm-return iterable<array-key, array{
      *     content: array{
      *         comment: array|scalar,
      *         rating?: int|array
      *     }
      * }>
    */
    private static function provideValidationErrorCases(): iterable
    {
        yield 'wrong_types' => [
            'content' => ['comment' => 1, 'rating' => []],
        ];

        yield 'required_parameter_is_absent' => [
            'content' => ['comment' => []],
        ];
    }

    public function testMalformedDataError(): void
    {
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

    public function testInvalidRequestContentTypeException(): void
    {
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
            'invalid_request_content_type',
            'Invalid request content type'
        );
    }

    public function testExtraAttributesError(): void
    {
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
                'rating333' => 1,
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'invalid_request_body_format',
            'Invalid request body format'
        );
    }
}
