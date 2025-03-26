<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class LoginController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'user' => $user ? $user->getUserIdentifier() : null,
        ]);
    }
}
