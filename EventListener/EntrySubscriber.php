<?php
namespace Kna\AccountingBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Kna\AccountingBundle\Model\EntryInterface;

class EntrySubscriber implements  EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entry = $args->getObject();

        if ($entry instanceof EntryInterface) {
            $entry->setCreatedAt(new \DateTime());
        }
    }
}