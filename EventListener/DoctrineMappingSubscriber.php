<?php
namespace Kna\AccountingBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineMappingSubscriber implements  EventSubscriber
{
    /**
     * @var string
     */
    protected $accountClass;
    /**
     * @var string
     */
    protected $entryClass;
    /**
     * @var string
     */
    protected $eventClass;
    /**
     * @var array
     */
    protected $eventDiscriminatorMap;

    public function __construct(
        string $accountClass,
        string $entryClass,
        string $eventClass,
        array $eventDiscriminatorMap
    )
    {
        $this->accountClass = $accountClass;
        $this->entryClass = $entryClass;
        $this->eventClass = $eventClass;
        $this->eventDiscriminatorMap = $eventDiscriminatorMap;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    protected function buildAccountMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->createOneToMany('entries', $this->entryClass)
            ->mappedBy('account')
            ->cascadeAll()
            ->fetchExtraLazy()
            ->build()
        ;
    }

    protected function buildEntryMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->createManyToOne('account', $this->accountClass)
            ->inversedBy('entries')
            ->build()
        ;

        $builder
            ->createManyToOne('event', $this->eventClass)
            ->inversedBy('resultingEntries')
            ->build()
        ;
    }

    protected function buildEventMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder
            ->setJoinedTableInheritance()
            ->setDiscriminatorColumn('type', 'string')
        ;

        foreach ($this->eventDiscriminatorMap as $name =>$class) {
            $builder->addDiscriminatorMapClass($name, $class);
        }

        $builder
            ->createOneToMany('resultingEntries', $this->entryClass)
            ->mappedBy('event')
            ->cascadeAll()
            ->orphanRemoval()
            ->build()
        ;

    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();

        switch (true) {
            case $metadata->getName() === $this->accountClass:
                $this->buildAccountMetadata($metadata);
                break;
            case $metadata->getName() === $this->entryClass:
                $this->buildEntryMetadata($metadata);
                break;
            case $metadata->getName() === $this->eventClass:
                $this->buildEventMetadata($metadata);
                break;
        }

    }
}