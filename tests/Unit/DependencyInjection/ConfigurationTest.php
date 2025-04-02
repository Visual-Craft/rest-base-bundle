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
     * @dataProvider provideZoneValidCases
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

    public static function provideZoneValidCases(): iterable
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
                    'methods' => ['POST'],
                ],
            ],
            [
                [
                    'path' => null,
                    'host' => null,
                    'methods' => ['POST'],
                    'ips' => [],
                ],
            ],
        ];
        yield [
            [
                [
                    'ips' => ['192.168.1.0/24'],
                ],
            ],
            [
                [
                    'path' => null,
                    'host' => null,
                    'methods' => [],
                    'ips' => ['192.168.1.0/24'],
                ],
            ],
        ];
        yield [
            [
                [
                    'path' => 'testPath',
                    'host' => 'testHost',
                    'methods' => ['POST'],
                    'ips' => ['192.168.1.0/24'],
                ],
            ],
            [
                [
                    'path' => 'testPath',
                    'host' => 'testHost',
                    'methods' => ['POST'],
                    'ips' => ['192.168.1.0/24'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideDebugValidCases
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

    public static function provideDebugValidCases(): iterable
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
     * @dataProvider provideMimeTypesValidCases
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

    public static function provideMimeTypesValidCases(): iterable
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
