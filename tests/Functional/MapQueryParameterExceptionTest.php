<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

/**
 * @internal
 * @psalm-suppress ClassMustBeFinal
 */
class MapQueryParameterExceptionTest extends FunctionalTestCase
{
    #[\Override]
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
            '/api/map-query-parameter',
            [
                'ids' => [1, 2],
                'firstName' => 'Test first name',
            ],
        );

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideNotFoundWhenRequiredParameterIsAbsentOrInvalidTypeCases
     */
    public function testNotFoundWhenRequiredParameterIsAbsentOrInvalidType(array $content): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/map-query-parameter',
            $content,
        );

        $this->assertSame(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * @psalm-return iterable<array-key, array{
     *     content: array{
     *         ids?: array|string,
     *         firstName?: string|array
     *     }
     * }>
     */
    private static function provideNotFoundWhenRequiredParameterIsAbsentOrInvalidTypeCases(): iterable
    {
        yield 'wrong_types' => [
            'content' => ['ids' => 'test', 'firstName' => []],
        ];
        yield 'required_parameter_is_absent' => [
            'content' => ['ids' => []],
        ];
    }
}
