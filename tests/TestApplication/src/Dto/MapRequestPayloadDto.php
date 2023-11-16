<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class MapRequestPayloadDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 500)]
    public string $comment;

    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\LessThanOrEqual(5)]
    public int $rating;

    public function __construct(string $comment, int $rating)
    {
        $this->comment = $comment;
        $this->rating = $rating;
    }
}
