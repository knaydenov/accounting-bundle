<?php
namespace Kna\AccountingBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Money\Currency;
use Money\Money;

abstract class AbstractAccount implements AccountInterface
{
    /**
     * @var Currency|null
     */
    protected $currency;
    /**
     * @var Money|null
     */
    protected $balance;
    /**
     * @var EntryInterface[]|Collection
     */
    protected $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getBalance(): ?Money
    {
        return $this->balance;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrency(?Currency $currency): void
    {
        $this->currency = $currency;

        $this->recalculateBalance();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    /**
     * {@inheritDoc}
     */
    public function addEntry(EntryInterface $entry): void
    {
        if (!$this->entries->contains($entry)) {
            $entry->setAccount($this);
            $this->entries->add($entry);

            $this->recalculateBalance();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeEntry(EntryInterface $entry): void
    {
        if ($this->entries->contains($entry)) {
            $entry->setAccount(null);
            $this->entries->removeElement($entry);

            $this->recalculateBalance();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeEntries(): void
    {
        foreach ($this->entries as $entry) {
            $this->removeEntry($entry);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function recalculateBalance(): void
    {
        $this->balance = new Money(0, $this->currency);
        foreach ($this->entries as $row) {
            $this->balance = $this->balance->add($row->getAmount());
        }
    }
}