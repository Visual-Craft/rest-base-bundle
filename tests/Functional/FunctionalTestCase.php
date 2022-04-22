<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class FunctionalTestCase extends WebTestCase
{
    protected function assertProblemResponse(Response $response, int $statusCode, string $type, string $title): void
    {
        $this->assertSame($statusCode, $response->getStatusCode());

        $responseContent = $response->getContent();

        $this->assertJson($responseContent);

        $decodedResponse = json_decode($responseContent, true);

        $this->assertSame($title, $decodedResponse['title']);
        $this->assertSame($type, $decodedResponse['type']);
        $this->assertSame($statusCode, $decodedResponse['statusCode']);
    }
}
