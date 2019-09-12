<?php
namespace Kna\AccountingBundle\DependencyInjection;


use Kna\AccountingBundle\Accounting\BaseEntryProvider;
use Kna\AccountingBundle\Accounting\EntryProviderInterface;
use Kna\AccountingBundle\Accounting\BaseEventProvider;
use Kna\AccountingBundle\Accounting\EventProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class KnaAccountingExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('kna_accounting.account_class', $config['account_class']);
        $container->setParameter('kna_accounting.entry_class', $config['entry_class']);
        $container->setParameter('kna_accounting.event_class', $config['event_class']);
        $container->setParameter('kna_accounting.event_discriminator_map', $config['event_discriminator_map']);

        $container
            ->registerForAutoconfiguration(EntryProviderInterface::class)
            ->addTag('kna_accounting.entry_provider')
        ;

        $container
            ->registerForAutoconfiguration(EventProviderInterface::class)
            ->addTag('kna_accounting.event_provider')
        ;

        $container
            ->registerForAutoconfiguration(BaseEntryProvider::class)
            ->addMethodCall('setEntryClass', ['%kna_accounting.entry_class%'])
            ->addMethodCall('setTranslator', [new Reference('translator')])
        ;

        $container
            ->registerForAutoconfiguration(BaseEventProvider::class)
            ->addMethodCall('setEventClass', ['%kna_accounting.event_class%'])
        ;

        $loader->load('entry.yaml');
        $loader->load('event.yaml');
        $loader->load('doctrine.yaml');
    }
}