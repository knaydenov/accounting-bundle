<?php
namespace Kna\AccountingBundle\Model;


use Money\Money;

interface EntryInterface
{
    /**
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface;

    /**
     * @param AccountInterface|null $account
     */
    public function setAccount(?AccountInterface $account): void;

    /**
     * @return EventInterface|null
     */
    public function getEvent(): ?EventInterface;

    /**
     * @param EventInterface|null $event
     */
    public function setEvent(?EventInterface $event): void;

    /**
     * @return Money|null
     */
    public function getAmount(): ?Money;

    /**
     * @param Money|null $amount
     */
    public function setAmount(?Money $amount): void;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime;

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void;
}