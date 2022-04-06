<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException;

class ThrowInvalidRequestExceptionController extends AbstractController
{
    public function __invoke(Request $request)
    {
        throw new InvalidRequestException();
    }
}
