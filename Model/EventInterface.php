<?php
namespace Kna\AccountingBundle\Model;


use Doctrine\Common\Collections\Collection;
use Money\Money;

interface EventInterface
{
    public const NAME = 'kna_accounting.event';

    /**
     * @return \DateTime|null
     */
    public function getOccurredAt(): ?\DateTime;

    /**
     * @param \DateTime|null $occurredAt
     */
    public function setOccurredAt(?\DateTime $occurredAt): void;

    /**
     * @return \DateTime|null
     */
    public function getNoticedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $noticedAt
     */
    public function setNoticedAt(?\DateTime $noticedAt): void;

    /**
     * @return Money|null
     */
    public function getAmount(): ?Money;

    /**
     * @param Money|null $amount
     */
    public function setAmount(?Money $amount): void;

    /**
     * @return bool
     */
    public function isProcessed(): bool;

    /**
     * @param bool $processed
     */
    public function setProcessed(bool $processed): void;

    /**
     * @return Collection|EntryInterface[]
     */
    public function getResultingEntries(): Collection;

    /**
     * @param Collection|EntryInterface[] $resultingEntries
     */
    public function setResultingEntries($resultingEntries): void;

    /**
     * @param EntryInterface $entry
     */
    public function addResultingEntry(EntryInterface $entry): void;

    /**
     * @param EntryInterface $entry
     */
    public function removeResultingEntry(EntryInterface $entry): void;

    /**
     *
     */
    public function removeResultingEntries(): void ;

}