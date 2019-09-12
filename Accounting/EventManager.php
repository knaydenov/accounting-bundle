<?php


namespace Kna\AccountingBundle\Accounting;


use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Kna\AccountingBundle\Model\EventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var EventFactory
     */
    protected $eventFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        EventFactory $eventFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->eventFactory = $eventFactory;
    }

    /**
     * @param EventInterface $event
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\PessimisticLockException
     */
    public function process(EventInterface $event): void
    {
        $this->entityManager->getConnection()->beginTransaction();
        $this->entityManager->lock($event, LockMode::PESSIMISTIC_WRITE);
        try {
            $this->eventDispatcher->dispatch($event, EventInterface::NAME);

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (\Exception $exception) {
            $this->entityManager->getConnection()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param $source
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function processSource($source): void
    {
        foreach ($this->eventFactory->fromSource($source) as $event) {
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $this->entityManager->persist($event);
                $this->entityManager->flush();

                $this->process($event);

                $this->entityManager->getConnection()->commit();
            } catch (\Exception $exception) {
                $this->entityManager->getConnection()->rollBack();
                throw $exception;
            }
        }
    }
}