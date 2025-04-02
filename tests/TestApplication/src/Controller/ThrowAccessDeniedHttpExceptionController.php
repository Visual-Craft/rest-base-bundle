<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class ThrowAccessDeniedHttpExceptionController extends AbstractController
{
    public function __invoke(): void
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
    }
}
