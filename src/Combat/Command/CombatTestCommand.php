<?php

declare(strict_types=1);

namespace GC\Combat\Command;

use GC\Combat\Mapper\MapperInterface;
use GC\Combat\Model\Battle;
use GC\Combat\Model\BattleInterface;
use GC\Combat\Service\CombatServiceInterface;
use GC\Universe\Model\UniverseRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CombatTestCommand extends Command
{
    private const COMMAND_NAME = 'app:combat:test';
    private const COMMAND_DESCRIPTION = 'Creates a combat test.';

    /**
     * @var \GC\Combat\Service\CombatServiceInterface
     */
    private $combatService;

    /**
     * @var \GC\Universe\Model\UniverseRepository
     */
    private $universeRepository;

    /**
     * @var \GC\Combat\Mapper\MapperInterface
     */
    private $mapper;

    /**
     * @param \GC\Combat\Service\CombatServiceInterface $combatService
     * @param \GC\Universe\Model\UniverseRepository $universeRepository
     * @param \GC\Combat\Mapper\MapperInterface $mapper
     */
    public function __construct(
        CombatServiceInterface $combatService,
        UniverseRepository $universeRepository,
        MapperInterface $mapper
    ) {
        parent::__construct(static::COMMAND_NAME);
        $this->combatService = $combatService;
        $this->universeRepository = $universeRepository;
        $this->mapper = $mapper;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_DESCRIPTION);
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

        $settings = $this->mapper->mapSettings($this->universeRepository->findById(1));
        $battle = $this->createBattle();

        $result = $this->combatService->calculate($battle, $settings);
        $json = $this->combatService->formatJson($result, $settings);

        $output->writeln($json);

        return 0;
    }

    /**
     * @return \GC\Combat\Model\BattleInterface
     */
    protected function createBattle(): BattleInterface
    {
        return new Battle();
    }
}
