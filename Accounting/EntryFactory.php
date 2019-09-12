<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EntryInterface;
use Kna\AccountingBundle\Model\EventInterface;

class EntryFactory
{
    /**
     * @var EntryProviderRegistry
     */
    protected $registry;

    public function __construct(
        EntryProviderRegistry $registry
    )
    {
        $this->registry = $registry;
    }

    /**
     * @param EventInterface $event
     * @return \Generator|EntryInterface[]
     */
    public function fromEvent(EventInterface $event): \Generator
    {
        foreach ($this->registry->getProviders($event) as $provider) {
            foreach ($provider->createEntries($event) as $entry) {
                $event->addResultingEntry($entry);
                yield $entry;
            }
        }
    }
}