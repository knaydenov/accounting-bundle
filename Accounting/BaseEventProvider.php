<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EntryInterface;

abstract class BaseEventProvider implements EventProviderInterface
{
    /**
     * @var string
     */
    protected $eventClass;

    /**
     * @return string
     */
    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    /**
     * @param string $eventClass
     */
    public function setEventClass(string $eventClass): void
    {
        $this->eventClass = $eventClass;
    }

    /**
     * @return EntryInterface
     */
    protected function createEntry(): EntryInterface
    {
        return new $this->eventClass();
    }

}