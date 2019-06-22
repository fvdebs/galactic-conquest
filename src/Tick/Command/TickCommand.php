<?php

declare(strict_types=1);

namespace GC\Tick\Command;

use Throwable;
use GC\Tick\Executor\TickExecutorInterface;
use GC\Tick\Executor\TickExecutorResultInterface;
use GC\Universe\Model\Universe;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_filter;
use function usleep;

final class TickCommand extends Command
{
    private const COMMAND_NAME = 'app:tick:run';
    private const COMMAND_DESCRIPTION = 'Calculates a tick.';
    private const ARGUMENT_UNIVERSE_ID = 'universe-id';
    private const OPTION_FORCE = 'force';

    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var bool
     */
    private $async;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    private $style;

    /**
     * @var \GC\Tick\Executor\TickExecutorInterface
     */
    private $tickExecutor;

    /**
     * @param \GC\Tick\Executor\TickExecutorInterface $tickExecutor
     * @param string $baseDir
     * @param bool $async - currently disabled cause of deadlock in database. it cen be an improvement to surround all processes with one transaction instead for each process. Will think about it.
     */
    public function __construct(TickExecutorInterface $tickExecutor, string $baseDir, bool $async = false)
    {
        parent::__construct(static::COMMAND_NAME);
        $this->tickExecutor = $tickExecutor;
        $this->baseDir = $baseDir;
        $this->async = $async;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_DESCRIPTION);

        $this->addArgument(static::ARGUMENT_UNIVERSE_ID, InputArgument::OPTIONAL, static::ARGUMENT_UNIVERSE_ID)
            ->addOption(static::OPTION_FORCE);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Throwable
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->style = new SymfonyStyle($input, $output);

        if ($this->async === false) {
            return $this->executeTicksNoneAsync();
        }

        if (!$this->hasUniverseIdArgument()) {
            return $this->spawnAsyncProcessesForUniverses();
        }

        // async
        $universeId =  $this->getUniverseIdArgument();
        if (!$this->isCalculationNeededFor($universeId)) {
            return 0;
        }

        $this->outputResult(
            $this->startCalculationFor($universeId)
        );

        return 0;
    }

    /**
     * @param \GC\Tick\Executor\TickExecutorResultInterface $tickExecutorResult
     *
     * @return void
     */
    protected function outputResult(TickExecutorResultInterface $tickExecutorResult): void
    {
        $this->style->newLine();

        if (!$tickExecutorResult->isSuccessful()) {
            foreach ($tickExecutorResult->getExceptions() as $exception) {
                $this->style->caution(
                    sprintf("%s: %s \n\n %s:%s",
                        $tickExecutorResult->getUniverse()->getName(),
                        $exception->getMessage(),
                        $exception->getFile(),
                        $exception->getLine()
                    )
                );
            }

            return;
        }

        $data = [];
        foreach ($tickExecutorResult->getPluginResults() as $tickPluginResult) {
            $data[] = [
                $tickPluginResult->getPluginName(),
                $tickPluginResult->getTime(),
                $tickPluginResult->getAffectedRows(),
                $tickPluginResult->getDataAsString()
            ];
        }

        $table = new Table($this->output);
        $table->setHeaderTitle($tickExecutorResult->getUniverse()->getName());
        $table->setHeaders(['name', 'sec', 'affected rows', 'data']);
        $table->setRows($data);
        $table->setFooterTitle($tickExecutorResult->getTimeOverall() . ' sec');
        $table->render();

        $this->style->newLine();
    }

    /**
     * @return int
     */
    private function executeTicksNoneAsync(): int
    {
        foreach ($this->tickExecutor->findUniversesWhichNeedsCalculation() as $universe) {
            if (!$this->isCalculationNeededFor($universe->getUniverseId())) {
                continue;
            }

            if ($this->hasUniverseIdArgument() && $this->getUniverseIdArgument() !== $universe->getUniverseId()) {
                continue;
            }

            $this->outputResult(
                $this->startCalculationFor($universe->getUniverseId())
            );
        }

        return 0;
    }

    /**
     * @param int $universeId
     *
     * @return bool
     */
    private function isCalculationNeededFor(int $universeId): bool
    {
        return $this->tickExecutor->isCalculationNeeded($universeId, $this->hasForceOption());
    }

    /**
     * @param int $universeId
     *
     * @return \GC\Tick\Executor\TickExecutorResultInterface
     */
    private function startCalculationFor(int $universeId): TickExecutorResultInterface
    {
        try {
            return $this->tickExecutor->calculate($universeId);
        } catch (Throwable $throwable) {
            $this->output->writeln($throwable->getMessage());
            throw $throwable;
        }
    }

    /**
     * @return int
     */
    protected function spawnAsyncProcessesForUniverses(): int
    {
        $processList = [];
        foreach ($this->tickExecutor->findUniversesWhichNeedsCalculation() as $universe) {
            $process = new Process(
                $this->getAsyncProcessArgumentsFor($universe)
            );

            $process->start();

            $processList[] = $process;
        }

        foreach ($processList as $process) {
            while ($process->isRunning()) {
                usleep(200000);
            }

            $this->output->writeln($process->getOutput());
        }

        return 0;
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
            $this->hasForceOption() ? '--' . static::OPTION_FORCE : null,
        ], 'strlen');
    }

    /**
     * @return bool
     */
    protected function hasUniverseIdArgument(): bool
    {
        return $this->input->getArgument(static::ARGUMENT_UNIVERSE_ID) !== null;
    }

    /**
     * @return int
     */
    protected function getUniverseIdArgument(): int
    {
        return (int) $this->input->getArgument(static::ARGUMENT_UNIVERSE_ID);
    }

    /**
     * @return bool
     */
    protected function hasForceOption(): bool
    {
        return $this->input->getOption(static::OPTION_FORCE) === true;
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
}
