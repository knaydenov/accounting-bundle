<?php
namespace Kna\AccountingBundle\Model;


use Doctrine\Common\Collections\Collection;
use Money\Currency;
use Money\Money;

interface AccountInterface
{
    /**
     * @return Money
     */
    public function getBalance(): ?Money;

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency;

    /**
     * @param Currency|null $currency
     */
    public function setCurrency(?Currency $currency): void;

    /**
     * @return Collection
     */
    public function getEntries(): Collection;

    /**
     * @param EntryInterface $entry
     */
    public function addEntry(EntryInterface $entry): void;

    /**
     * @param EntryInterface $entry
     */
    public function removeEntry(EntryInterface $entry): void;

    /**
     *
     */
    public function removeEntries(): void;

    /**
     *
     */
    public function recalculateBalance(): void;
}