<?php

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FunctionalTestCase extends WebTestCase
{
    protected function assertProblemResponse(Response $response, int $statusCode, string $type, string $title): void
    {
        $this->assertEquals($statusCode, $response->getStatusCode());

        $responseContent = $response->getContent();

        $this->assertJson($responseContent);

        $decodedResponse = json_decode($responseContent, true);

        $this->assertEquals($title, $decodedResponse['title']);
        $this->assertEquals($type, $decodedResponse['type']);
        $this->assertEquals($statusCode,$decodedResponse['statusCode']);
    }
}
