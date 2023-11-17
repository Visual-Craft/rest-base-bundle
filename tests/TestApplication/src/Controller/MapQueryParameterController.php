<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use VisualCraft\RestBaseBundle\Tests\TestApplication\Dto\MapQueryStringDto;

class MapQueryParameterController extends AbstractController
{
    /** @psalm-suppress UndefinedAttributeClass, ParseError */
    public function __invoke(
        #[MapQueryParameter]
        array $ids,
        #[MapQueryParameter]
        string $firstName,
    ): JsonResponse
    {
        return new JsonResponse([]);
    }
}
