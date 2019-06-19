<?php

declare(strict_types=1);

namespace GC\Universe\Command;

use Doctrine\ORM\EntityManager;
use GC\Universe\Model\Universe;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;
use GC\Universe\Model\UniverseRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_filter;
use function round;
use function microtime;
use function sprintf;
use function usleep;

final class TickCommand extends Command
{
    private const COMMAND_NAME = 'app:tick:run';
    private const COMMAND_DESCRIPTION = 'Calculates a tick.';
    private const ARGUMENT_UNIVERSE_ID = 'universe-id';
    private const OPTION_FORCE_TICK = 'force-tick';
    private const OPTION_FORCE_RANKING = 'force-ranking';

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
     * @var string
     */
    private $baseDir;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $baseDir
     */
    public function __construct(
        UniverseRepository $universeRepository,
        EntityManager $entityManager,
        LoggerInterface $logger,
        string $baseDir
    ) {
        parent::__construct(static::COMMAND_NAME);
        $this->universeRepository = $universeRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->baseDir = $baseDir;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_DESCRIPTION);

        $this->addArgument(static::ARGUMENT_UNIVERSE_ID, InputArgument::OPTIONAL, static::ARGUMENT_UNIVERSE_ID)
            ->addOption(static::OPTION_FORCE_TICK)
            ->addOption(static::OPTION_FORCE_RANKING);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Throwable
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $universeId = $input->getArgument(static::ARGUMENT_UNIVERSE_ID);
        if ($universeId === null) {
            $this->spawnAsyncProcessesForTickCalculation();
            return 0;
        }

        // from now on async
        $start = $this->startMicrotime();

        $universe = $this->universeRepository->findById((int) $universeId);

        $this->startCalculationFor($universe);

        $end = $this->endMicrotime($start);
        $this->output->writeln(sprintf('%s overall (%s players) %s sec', $universe->getName(), $universe->getPlayerCount(), $end));

        return 0;
    }

    /**
     * @async
     *
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    protected function startCalculationFor(Universe $universe): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            if ($this->hasForceTickOption() || $universe->isTickCalculationNeeded()) {
                $this->calculateTickOf($universe);
            }

            if ($this->hasForceRankingOption() || $universe->isAllianceAndGalaxyRankingGenerationNeeded()) {
                $this->calculateRankingOf($universe);
            }

            $start = $this->startMicrotime();

            $this->entityManager->flush();
            $this->entityManager->commit();

            $end = $this->endMicrotime($start);
            $this->output->writeln(sprintf('%s commit %s sec', $universe->getName(), $end));

        } catch (Throwable $throwable) {
            $this->entityManager->rollback();
            $this->logger->error($throwable);
            throw $throwable;
        }
    }

    /**
     * @return bool
     */
    protected function hasForceTickOption(): bool
    {
        return $this->input->getOption(static::OPTION_FORCE_TICK) == true;
    }

    /**
     * @return bool
     */
    protected function hasForceRankingOption(): bool
    {
        return $this->input->getOption(static::OPTION_FORCE_RANKING) == true;
    }

    /**
     * @return void
     */
    protected function spawnAsyncProcessesForTickCalculation(): void
    {
        $start = $this->startMicrotime();

        $processList = [];
        foreach ($this->universeRepository->findStartedAndActiveUniverses() as $universe) {
            $process = new Process($this->getAsyncProcessArgumentsFor($universe));
            $process->start();

            $processList[] = $process;
        }

        foreach ($processList as $process) {
            while ($process->isRunning()) {
                usleep(200000);
            }

            $this->output->write($process->getOutput());
        }

        $end = $this->endMicrotime($start);
        $this->output->writeln(sprintf('overall %s sec', $end));
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return string[]
     */
    protected function getAsyncProcessArgumentsFor(Universe $universe): array
    {
        return array_filter([
            $this->getPathToPhpExecutable(),
            $this->getPathToCommand(),
            static::COMMAND_NAME,
            $universe->getUniverseId(),
            $this->hasForceTickOption() ? '--' . static::OPTION_FORCE_TICK : null,
            $this->hasForceRankingOption() ? '--' . static::OPTION_FORCE_RANKING : null,
        ], 'strlen');
    }

    /**
     * @return string
     */
    protected function getPathToCommand(): string
    {
        return $this->baseDir . '/vendor/bin/inferno';
    }

    /**
     * @return string
     */
    protected function getPathToPhpExecutable(): string
    {
        return (new PhpExecutableFinder())->find();
    }

    /**
     * @return float
     */
    protected function startMicrotime(): float
    {
        return microtime(true);
    }

    /**
     * @param float $start
     *
     * @return float
     */
    protected function endMicrotime(float $start): float
    {
        return round(microtime(true) - $start, 2);
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    protected function calculateTickOf(Universe $universe): void
    {
        $start = $this->startMicrotime();
        $universe->tick();
        $end = $this->endMicrotime($start);
        $this->output->writeln(sprintf('%s tick %s sec', $universe->getName(), $end));
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    protected function calculateRankingOf(Universe $universe): void
    {
        $start = $this->startMicrotime();
        $universe->generateAllianceAndGalaxyRanking();
        $end = $this->endMicrotime($start);
        $this->output->writeln(sprintf('%s ranking %s sec', $universe->getName(), $end));
    }
}
