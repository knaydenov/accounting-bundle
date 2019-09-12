<?php
namespace Kna\AccountingBundle\Command;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kna\AccountingBundle\Accounting\EventManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessEventsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $eventClass;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Processes pending accounting events')
            ->setHelp('Processes pending accounting events')
            ->addOption('loop', null, InputOption::VALUE_NONE, 'Run in a loop?')
            ->addOption('delay', null, InputOption::VALUE_REQUIRED, 'Delay between ticks (sec)', 10)
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityRepository $eventRepository */
        $eventRepository = $this->entityManager->getRepository($this->eventClass);

        $queryBuilder = $eventRepository->createQueryBuilder('e');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('e.processed', ':processed'))
            ->setParameter('processed', false)
        ;

        if ($input->getOption('loop')) {
            $output->writeln(sprintf('Processing events in a <info>loop</info> with delay of <info>%d</info> sec between ticks', $input->getOption('delay')));
        } else {
            $output->writeln('Processing events ');
        }

        do {
            $iterableResult = $queryBuilder->getQuery()->iterate();
            foreach ($iterableResult as $row) {
                try {
                    if ($this->logger && method_exists($row[0], '__toString')) {
                        $this->logger->info(sprintf('Processing "%s" event', (string) $row[0]));
                    }

                    $this->eventManager->process($row[0]);
                } catch (\Exception $exception) {
                    if ($this->logger) {
                        $this->logger->error(sprintf('<error>Event processing failed: %s</error>', $exception->getMessage()));
                    }
                }
            }

            gc_collect_cycles();

            if ($input->getOption('loop')) {
                sleep($input->getOption('delay'));
            }

        } while ($input->getOption('loop'));
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string
     */
    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    /**
     * @param string $eventClass
     */
    public function setEventClass(string $eventClass): void
    {
        $this->eventClass = $eventClass;
    }

    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        return $this->eventManager;
    }

    /**
     * @param EventManager $eventManager
     */
    public function setEventManager(EventManager $eventManager): void
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

}