<?php
namespace Kna\AccountingBundle;


use Kna\AccountingBundle\DependencyInjection\Compiler\EntryProviderPass;
use Kna\AccountingBundle\DependencyInjection\Compiler\EventProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KnaAccountingBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new EntryProviderPass());
        $container->addCompilerPass(new EventProviderPass());
    }
}