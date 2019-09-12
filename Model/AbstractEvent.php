<?php
namespace Kna\AccountingBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Money\Money;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

abstract class AbstractEvent extends BaseEvent implements EventInterface
{
    /**
     * @var \DateTime|null
     */
    protected $occurredAt;
    /**
     * @var \DateTime|null
     */
    protected $noticedAt;
    /**
     * @var Money|null
     */
    protected $amount;
    /**
     * @var bool
     */
    protected $processed = false;
    /**
     * @var EntryInterface[]|Collection
     */
    protected $resultingEntries;

    public function __construct()
    {
        $this->resultingEntries = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getOccurredAt(): ?\DateTime
    {
        return $this->occurredAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setOccurredAt(?\DateTime $occurredAt): void
    {
        $this->occurredAt = $occurredAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getNoticedAt(): ?\DateTime
    {
        return $this->noticedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setNoticedAt(?\DateTime $noticedAt): void
    {
        $this->noticedAt = $noticedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getAmount(): ?Money
    {
        return $this->amount;
    }

    /**
     * {@inheritDoc}
     */
    public function setAmount(?Money $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritDoc}
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * {@inheritDoc}
     */
    public function setProcessed(bool $processed): void
    {
        $this->processed = $processed;
    }

    /**
     * {@inheritDoc}
     */
    public function getResultingEntries(): Collection
    {
        return $this->resultingEntries;
    }

    /**
     * {@inheritDoc}
     */
    public function setResultingEntries($resultingEntries): void
    {
        $this->resultingEntries = $resultingEntries;
    }

    /**
     * {@inheritDoc}
     */
    public function addResultingEntry(EntryInterface $entry): void
    {
        if (!$this->resultingEntries->contains($entry)) {
            $this->resultingEntries->add($entry);
            $entry->setEvent($this);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeResultingEntry(EntryInterface $entry): void
    {
        $this->resultingEntries->removeElement($entry);
        $entry->setEvent(null);
    }

    /**
     * {@inheritDoc}
     */
    public function removeResultingEntries(): void
    {
        foreach ($this->resultingEntries as $entry) {
            $this->removeResultingEntry($entry);
        }
    }

    abstract public function __toString();

}