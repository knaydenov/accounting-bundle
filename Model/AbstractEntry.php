<?php
namespace Kna\AccountingBundle\Model;


use Money\Money;

abstract class AbstractEntry implements EntryInterface
{
    /**
     * @var AccountInterface|null
     */
    protected $account;
    /**
     * @var EventInterface|null
     */
    protected $event;
    /**
     * @var Money|null
     */
    protected $amount;
    /**
     * @var string|null
     */
    protected $description;
    /**
     * @var \DateTime|null
     */
    protected $createdAt;

    /**
     * {@inheritDoc}
     */
    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    /**
     * {@inheritDoc}
     */
    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }

    /**
     * {@inheritDoc}
     */
    public function getEvent(): ?EventInterface
    {
        return $this->event;
    }

    /**
     * {@inheritDoc}
     */
    public function setEvent(?EventInterface $event): void
    {
        $this->event = $event;
        $event->addResultingEntry($this);
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}