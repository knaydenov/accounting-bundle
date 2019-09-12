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
                ->scalarNode('account_class')
                    ->defaultValue('App\\Entity\\Account')
                ->end()
                ->scalarNode('entry_class')
                    ->defaultValue('App\\Entity\\Entry')
                ->end()
                ->scalarNode('event_class')
                    ->defaultValue('App\\Entity\\Event')
                ->end()
                ->arrayNode('event_discriminator_map')
                    ->useAttributeAsKey('class')
                    ->scalarPrototype()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}