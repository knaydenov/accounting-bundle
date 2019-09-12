<?php
namespace Kna\AccountingBundle\DependencyInjection\Compiler;


use Kna\AccountingBundle\Accounting\EntryProviderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EntryProviderPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(EntryProviderRegistry::class)){
            return;
        }

        $definition = $container->findDefinition(EntryProviderRegistry::class);
        $taggedServices = $container->findTaggedServiceIds('kna_accounting.entry_provider');
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('register', array(new Reference($id)));
        }
    }
}