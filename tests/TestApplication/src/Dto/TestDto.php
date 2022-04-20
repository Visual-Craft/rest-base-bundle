<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class TestDto
{
    /**
     * @var string|null
     */
    private $field1;

    /**
     * @var string|null
     */
    private $field2;

    /**
     * @var string|null
     */
    private $field3;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('field1', new NotBlank());
    }

    /**
     * @return string|null
     */
    public function getField1(): ?string
    {
        return $this->field1;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setField1(?string $value): self
    {
        $this->field1 = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getField2(): ?string
    {
        return $this->field2;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setField2(?string $value): self
    {
        $this->field2 = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getField3(): ?string
    {
        return $this->field3;
    }

    /**
     * @param string|null $value
     * @return $this
     */
    public function setField3(?string $value): self
    {
        $this->field3 = $value;

        return $this;
    }
}
