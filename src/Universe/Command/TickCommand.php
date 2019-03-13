<?php

declare(strict_types=1);

namespace GC\Universe\Command;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Throwable;
use GC\Universe\Model\UniverseRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TickCommand extends Command
{
    /**
     * @var \GC\Universe\Model\UniverseRepository
     */
    private $universeRepository;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        UniverseRepository $universeRepository,
        EntityManager $entityManager,
        LoggerInterface $logger
    ) {
        parent::__construct('app:tick:run');
        $this->universeRepository = $universeRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:tick:run');
        $this->setDescription('Calculates a tick.');
        $this->setHelp('This command starts a tick.');

        $this->addOption('force-tick');
        $this->addOption('force-ranking');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Throwable
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            foreach ($this->universeRepository->findStartedAndActiveUniverses() as $universe) {
                if ($universe->isTickCalculationNeeded() || $input->getOption('force-tick') === true) {
                    $universe->tick();
                }

                if ($universe->isAllianceAndGalaxyRankingGenerationNeeded() || $input->getOption('force-ranking') === true) {
                    $universe->generateAllianceAndGalaxyRanking();
                }
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (Throwable $throwable) {
            $this->entityManager->rollback();
            $this->logger->error($throwable);
            throw $throwable;
        }

        return 0;
    }
}