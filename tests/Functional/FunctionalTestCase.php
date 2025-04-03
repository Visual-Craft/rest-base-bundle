<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
abstract class FunctionalTestCase extends WebTestCase
{
    #[\Override]
    protected function tearDown(): void
    {
        /** @psalm-suppress MixedMethodCall, PossiblyNullReference */
        static::getContainer()->get('cache.global_clearer')->clearPool('cache.rate_limiter');
        parent::tearDown();
    }

    protected function assertProblemResponse(Response $response, int $statusCode, string $type, string $title): void
    {
        $this->assertSame($statusCode, $response->getStatusCode());

        $responseContent = $response->getContent();
        $this->assertIsString($responseContent);
        $this->assertJson($responseContent);

        /** @var array{title: string, type: string, statusCode: int} $decodedResponse */
        $decodedResponse = json_decode($responseContent, true);

        $this->assertSame($title, $decodedResponse['title']);
        $this->assertSame($type, $decodedResponse['type']);
        $this->assertSame($statusCode, $decodedResponse['statusCode']);
    }
}
