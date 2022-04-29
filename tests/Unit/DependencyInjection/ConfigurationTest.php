<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use VisualCraft\RestBaseBundle\DependencyInjection\Configuration;

/**
 * @internal
 */
class ConfigurationTest extends WebTestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefaultValuesValid(): void
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
                        'xml' => 'application/xml',
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider zoneValidDataProvider
     */
    public function testZoneValid(array $configurationValues, array $expectedProcessedConfigurationValues): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'zone' => $configurationValues,
                ],
            ],
            [
                'zone' => $expectedProcessedConfigurationValues,
            ],
            'zone'
        );
    }

    public function zoneValidDataProvider(): iterable
    {
        yield [[], []];
        yield [
            [
                [
                    'path' => 'testPath',
                ],
            ],
            [
                [
                    'path' => 'testPath',
                    'host' => null,
                    'methods' => [],
                    'ips' => [],
                ],
            ],
        ];
        yield [
            [
                [
                    'host' => 'testHost',
                ],
            ],
            [
                [
                    'path' => null,
                    'host' => 'testHost',
                    'methods' => [],
                    'ips' => [],
                ],
            ],
        ];
        yield [
            [
                [
                    'methods' => ['testMethod'],
                ],
            ],
            [
                [
                    'path' => null,
                    'host' => null,
                    'methods' => ['testMethod'],
                    'ips' => [],
                ],
            ],
        ];
        yield [
            [
                [
                    'ips' => ['testIps'],
                ],
            ],
            [
                [
                    'path' => null,
                    'host' => null,
                    'methods' => [],
                    'ips' => ['testIps'],
                ],
            ],
        ];
        yield [
            [
                [
                    'path' => 'testPath',
                    'host' => 'testHost',
                    'methods' => ['testMethod'],
                    'ips' => ['testIps'],
                ],
            ],
            [
                [
                    'path' => 'testPath',
                    'host' => 'testHost',
                    'methods' => ['testMethod'],
                    'ips' => ['testIps'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider debugValidDataProvider
     */
    public function testDebugValid($configurationValues, $expectedProcessedConfigurationValues): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'debug' => $configurationValues,
                ],
            ],
            [
                'debug' => $expectedProcessedConfigurationValues,
            ],
            'debug'
        );
    }

    public function debugValidDataProvider(): iterable
    {
        yield [
            true,
            true,
        ];
        yield [
            false,
            false,
        ];
    }

    /**
     * @dataProvider mimeTypesValidDataProvider
     */
    public function testMimeTypesValid(array $configurationValues, array $expectedProcessedConfigurationValues): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'mimeTypes' => $configurationValues,
                ],
            ],
            [
                'mimeTypes' => $expectedProcessedConfigurationValues,
            ],
            'mimeTypes'
        );
    }

    public function mimeTypesValidDataProvider(): iterable
    {
        yield [
            [
                'json' => 'application/json',
                'xml' => 'application/xml',
            ],
            [
                'json' => 'application/json',
                'xml' => 'application/xml',
            ],
        ];
        yield [
            ['json' => 'application/json'],
            ['json' => 'application/json'],
        ];
        yield [
            ['xml' => 'application/xml'],
            ['xml' => 'application/xml'],
        ];
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
