<?php
namespace Kna\AccountingBundle\DependencyInjection\Compiler;


use Kna\AccountingBundle\Accounting\EventProviderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EventProviderPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(EventProviderRegistry::class)){
            return;
        }

        $definition = $container->findDefinition(EventProviderRegistry::class);
        $taggedServices = $container->findTaggedServiceIds('kna_accounting.event_provider');
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('register', array(new Reference($id)));
        }
    }
}