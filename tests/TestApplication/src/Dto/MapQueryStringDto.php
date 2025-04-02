<?php

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class MapQueryStringDto
{
    #[Assert\Choice(['placed', 'shipped', 'delivered'])]
    public ?string $status;

    public float $total;

    public function __construct(?string $status, float $total)
    {
        $this->status = $status;
        $this->total = $total;
    }
}
