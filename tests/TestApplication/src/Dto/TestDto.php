<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\TestApplication\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TestDto
{
    /**
     * @var string|null
     * @Assert\NotBlank()
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
