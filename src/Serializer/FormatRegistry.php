<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Serializer;

class FormatRegistry
{
    /**
     * @var array
     */
    private $formatToMimeTypeMap;

    /**
     * @var array
     */
    private $mimeTypeMapToFormat;

    public function __construct(array $formatToMimeTypeMap)
    {
        $this->formatToMimeTypeMap = $formatToMimeTypeMap;
        $this->mimeTypeMapToFormat = array_flip($this->formatToMimeTypeMap);
    }

    public function getMimeTypeByFormat(string $value): ?string
    {
        return $this->formatToMimeTypeMap[$value] ?? null;
    }

    public function getFormatByMimeType(string $value): ?string
    {
        return $this->mimeTypeMapToFormat[$value] ?? null;
    }

    public function isFormatSupported(string $value): bool
    {
        return isset($this->formatToMimeTypeMap[$value]);
    }

    public function isMimeTypeSupported(string $value): bool
    {
        return isset($this->mimeTypeMapToFormat[$value]);
    }

    public function getSupportedFormats(): array
    {
        return array_keys($this->formatToMimeTypeMap);
    }

    public function getSupportedMimeTypes(): array
    {
        return array_keys($this->mimeTypeMapToFormat);
    }
}
