<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem;

interface ExceptionToProblemConverterInterface
{
    public function convert(\Throwable $exception): ?Problem;
}
