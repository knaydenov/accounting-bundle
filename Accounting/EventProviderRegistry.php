<?php
namespace Kna\AccountingBundle\Accounting;


class EventProviderRegistry
{
    /** @var EventProviderInterface[] */
    protected $providers = [];

    public function register(EventProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * @param mixed|null $source
     * @return \Generator|EventProviderInterface[]
     */
    public function getProviders($source): \Generator
    {
        foreach ($this->providers as $provider) {
            if ((!$source) || ($source && $provider->supports($source))) {
                yield $provider;
            }
        }
    }
}