<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EventInterface;

class EntryProviderRegistry
{
    /** @var EntryProviderInterface[] */
    protected $providers = [];

    public function register(EntryProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * @param EventInterface|null $event
     * @return \Generator|EntryProviderInterface[]
     */
    public function getProviders(?EventInterface $event): \Generator
    {
        foreach ($this->providers as $provider) {
            if ((!$event) || ($event && $provider->supports($event))) {
                yield $provider;
            }
        }
    }
}