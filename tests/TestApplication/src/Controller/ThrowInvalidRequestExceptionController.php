<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use VisualCraft\RestBaseBundle\Exceptions\InvalidRequestException;

/**
 * @psalm-suppress DeprecatedClass, ClassMustBeFinal
 */
class ThrowInvalidRequestExceptionController extends AbstractController
{
    public function __invoke(): void
    {
        throw new InvalidRequestException();
    }
}
