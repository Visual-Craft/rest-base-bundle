<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('visual_craft_rest_base');
        /**
         * @psalm-suppress PossiblyNullReference, UndefinedInterfaceMethod, MixedMethodCall
         */
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('zone')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->defaultNull()
                            ->end()
                            ->scalarNode('host')
                                ->defaultNull()
                            ->end()
                            ->arrayNode('methods')
                                ->beforeNormalization()->ifString()->then(static fn (string $v) => preg_split('/\s*,\s*/', $v))->end()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('ips')
                                ->beforeNormalization()->ifString()->then(static fn (string $v) => [$v])->end()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
                ->arrayNode('mimeTypes')
                    ->defaultValue(['json' => 'application/json'])
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
