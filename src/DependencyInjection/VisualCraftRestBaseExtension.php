<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\HostRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\IpsRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\PathRequestMatcher;
use Symfony\Component\HttpKernel\KernelEvents;
use VisualCraft\RestBaseBundle\Controller\ErrorController;
use VisualCraft\RestBaseBundle\EventListener\ZoneMatchListener;
use VisualCraft\RestBaseBundle\Problem\ExceptionToProblemConverterInterface;
use VisualCraft\RestBaseBundle\Problem\ProblemResponseFactory;
use VisualCraft\RestBaseBundle\Serializer\FormatRegistry;

class VisualCraftRestBaseExtension extends Extension implements PrependExtensionInterface
{
    private const ZONE_REQUEST_MATCHER_TAG = 'visual_craft.rest_base.zone_request_matcher';
    private const EXCEPTION_TO_PROBLEM_CONVERTER_TAG = 'visual_craft.rest_base.exception_to_problem_converter';

    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        /**
         * @psalm-var array{
         *     zone: list<array{path: string|null, host: string|null, methods: list<string>, ips: list<string>}>,
         *     debug: bool|string,
         *     mimeTypes: array<string, string>
         * }
         */
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        /** @psalm-suppress ReservedWord */
        $loader->load('services.yaml');
        $this->configureZoneMatchListener($container, $config['zone']);
        $this->configureProblemBuilders($container, $config['debug']);
        $this->configureSerializer($container, $config['mimeTypes']);
    }

    #[\Override]
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'error_controller' => ErrorController::class,
        ]);
    }

    /**
     * @psalm-param list<array{path: string|null, host: string|null, methods: list<string>, ips: list<string>}> $zoneConfig
     * @psalm-suppress UndefinedClass
     */
    private function configureZoneMatchListener(ContainerBuilder $container, array $zoneConfig): void
    {
        if (!$zoneConfig) {
            return;
        }

        $matcherIndex = 0;

        foreach ($zoneConfig as $item) {
            if (!class_exists(ChainRequestMatcher::class)) {
                /** @psalm-suppress DeprecatedClass */
                $matcherDefinition = new Definition(RequestMatcher::class, [
                    $item['path'],
                    $item['host'],
                    $item['methods'],
                    $item['ips'],
                ]);
            } else {
                $matchers = [];
                $path = $item['path'];

                if ($path !== null) {
                    $matchers[] = new Definition(PathRequestMatcher::class, [$path]);
                }

                $host = $item['host'];

                if ($host !== null) {
                    $matchers[] = new Definition(HostRequestMatcher::class, [$host]);
                }

                $methods = $item['methods'];

                if ($methods) {
                    $matchers[] = new Definition(MethodRequestMatcher::class, [$methods]);
                }

                $ips = $item['ips'];

                if ($ips) {
                    $matchers[] = new Definition(IpsRequestMatcher::class, [$ips]);
                }
                $matcherDefinition = new Definition(ChainRequestMatcher::class);
                $matcherDefinition->setArguments([$matchers]);
            }

            $matcherDefinition->addTag(self::ZONE_REQUEST_MATCHER_TAG);
            $container->setDefinition(self::ZONE_REQUEST_MATCHER_TAG . '.instance_' . $matcherIndex++, $matcherDefinition);
        }

        $listenerDefinition = new Definition(ZoneMatchListener::class);
        $listenerDefinition->addTag('kernel.event_listener', [
            'event' => KernelEvents::REQUEST,
            'method' => 'onKernelRequest',
            'priority' => 248,
        ]);
        $listenerDefinition->setArgument('$requestMatchers', new TaggedIteratorArgument(self::ZONE_REQUEST_MATCHER_TAG));
        $container->setDefinition(ZoneMatchListener::class, $listenerDefinition);
    }

    /**
     * @param bool|string $debugConfig
     */
    private function configureProblemBuilders(ContainerBuilder $container, $debugConfig): void
    {
        $container
            ->registerForAutoconfiguration(ExceptionToProblemConverterInterface::class)
            ->addTag(self::EXCEPTION_TO_PROBLEM_CONVERTER_TAG)
        ;
        $container->getDefinition(ProblemResponseFactory::class)
            ->setArgument('$exceptionToProblemConverters', new TaggedIteratorArgument(self::EXCEPTION_TO_PROBLEM_CONVERTER_TAG))
            ->setArgument('$debug', $debugConfig)
        ;
    }

    /**
     * @psalm-param array<string, string> $mimeTypesConfig
     */
    private function configureSerializer(ContainerBuilder $container, array $mimeTypesConfig): void
    {
        $container->getDefinition(FormatRegistry::class)
            ->setArgument('$formatToMimeTypeMap', $mimeTypesConfig)
        ;
    }
}
