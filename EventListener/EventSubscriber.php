<?php
namespace Kna\AccountingBundle\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Kna\AccountingBundle\Accounting\EntryFactory;
use Kna\AccountingBundle\Model\EventInterface;

class EventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntryFactory
     */
    protected $entryFactory;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(
        EntryFactory $entryFactory,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->entryFactory = $entryFactory;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [EventInterface::NAME => 'onEvent'];
    }

    public function onEvent(EventInterface $event): void
    {
        if ($event->isProcessed()) {
            return;
        }

        $this->eventDispatcher->dispatch($event);

        foreach ($this->entryFactory->fromEvent($event) as $entry) {
            $this->entityManager->persist($entry);
        }

        $event->setProcessed(true);
        $this->entityManager->persist($event);
    }
}