<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EntryInterface;
use Kna\AccountingBundle\Model\EventInterface;

interface EntryProviderInterface
{
    /**
     * @param EventInterface $event
     * @return bool
     */
    public function supports(EventInterface $event): bool;

    /**
     * @param EventInterface $event
     * @return \Generator|EntryInterface[]
     */
    public function createEntries(EventInterface $event): \Generator;
}