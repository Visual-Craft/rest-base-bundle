<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ThrowAccessDeniedHttpExceptionController extends AbstractController
{
    public function __invoke(Request $request): void
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
    }
}
