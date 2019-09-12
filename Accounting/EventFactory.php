<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EventInterface;

class EventFactory
{
    /**
     * @var EventProviderRegistry
     */
    protected $registry;

    /**
     * @var array
     */
    protected $eventDiscriminatorMap;

    public function __construct(
        EventProviderRegistry $registry,
        array $eventDiscriminatorMap
    )
    {
        $this->registry = $registry;
        $this->eventDiscriminatorMap = $eventDiscriminatorMap;
    }

    /**
     * @param string|EventInterface $event
     * @return string
     */
    protected function getName($event): string
    {
        $class = $event instanceof EventInterface ? get_class($event) : $event;
        return array_flip($this->eventDiscriminatorMap)[(string) $class] ?? 'unknown';
    }

    /**
     * @param mixed $source
     * @return \Generator
     */
    public function fromSource($source): \Generator
    {
        foreach ($this->registry->getProviders($source) as $provider) {
            foreach ($provider->createEvents($source) as $event) {
                yield $event;
            }
        }
    }
}