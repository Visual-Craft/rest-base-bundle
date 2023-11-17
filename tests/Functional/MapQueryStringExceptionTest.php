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
            'GET',
            '/api/map-query-string',
            [
                'status' => 'placed',
                'total' => 1,
            ],
        );

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testValidationErrorWhenValueIsInvalid(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/map-query-string',
            [
                'status' => 1,
            ],
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }

    public function testValidationErrorWhenRequiredQueryParamIsAbsent(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/map-query-string?status=placed',
            [
                'status' => 'placed',
            ],
        );

        $this->assertProblemResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'validation_error',
            'Validation error'
        );
    }

    public function testValidationErrorWhenQueryStringsAreAbsent(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/map-query-string',
        );

        $this->assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }
}
