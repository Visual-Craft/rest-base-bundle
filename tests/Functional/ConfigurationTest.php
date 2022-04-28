<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Functional;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use VisualCraft\RestBaseBundle\DependencyInjection\Configuration;

/**
 * @internal
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefaultValuesAreValid(): void
    {
        $this->assertConfigurationIsValid(
            [
                'visual_craft_rest_base' => [
                    'zone' => [
                        [
                            'path' => null,
                            'host' => null,
                            'methods' => [],
                            'ips' => [],
                        ],
                    ],
                    'debug' => true,
                    'mimeTypes' => [
                        'json' => 'application/json',
                        'xml' => 'application/xml'
                    ]
                ],
            ]
        );

        $this->assertProcessedConfigurationEquals(
            [
                [
                    'mimeTypes' => [
                        'xml' => 'application/xml',
                    ]
                ],
            ],
            [
                'mimeTypes' => [
                    'xml' => 'application/xml',
                    'json' => 'application/json',
                ]
            ],
            'mimeTypes'
        );

        $this->assertProcessedConfigurationEquals(
            [
                [
                    'mimeTypes' => [
                        'json' => 'application/json',
                    ]
                ],
            ],
            [
                'mimeTypes' => [
                    'xml' => 'application/xml',
                    'json' => 'application/json',
                ]
            ],
            'mimeTypes'
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
