<?php
namespace Kna\AccountingBundle\Accounting;


use Kna\AccountingBundle\Model\EntryInterface;

interface EventProviderInterface
{
    /**
     * @param mixed $source
     * @return bool
     */
    public function supports($source): bool;

    /**
     * @param mixed $source
     * @return \Generator|EntryInterface[]
     */
    public function createEvents($source): \Generator;
}