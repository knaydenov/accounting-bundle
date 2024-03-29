<?php
namespace Kna\AccountingBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('kna_accounting');

        $root
            ->children()
                ->arrayNode('account')
                    ->addDefaultsIfNotSet()
                    ->children()
                         ->scalarNode('class')
                            ->defaultValue('App\\Entity\\Account')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('entry')
                    ->addDefaultsIfNotSet()
                    ->children()
                         ->scalarNode('class')
                            ->defaultValue('App\\Entity\\Entry')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('event')
                    ->addDefaultsIfNotSet()
                    ->children()
                         ->scalarNode('class')
                            ->defaultValue('App\\Entity\\Event')
                        ->end()
                        ->arrayNode('discriminator_map')
                            ->useAttributeAsKey('class')
                            ->scalarPrototype()
                            ->end()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('discriminator_name')
                            ->defaultValue('type')
                        ->end()
                        ->scalarNode('discriminator_type')
                            ->defaultValue('string')
                        ->end()
                        ->scalarNode('discriminator_length')
                            ->defaultValue(255)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}