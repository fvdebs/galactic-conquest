<?php

declare(strict_types=1);

namespace GC\App\Command;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function sprintf;

final class SetupCommand extends Command
{
    private const COMMAND_NAME = 'app:setup';
    private const COMMAND_DESCRIPTION = 'Build a database dev environment (drop db if exists/create db/scheme up/run fixtures/run ticks).';
    private const ARGUMENT_NUMBER_OF_TICKS = 'Number of Ticks to run';

    /**
     * @var string
     */
    private $dbHost;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $dbPassword;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var \Symfony\Component\Console\Style\OutputStyle
     */
    protected $style;

    /**
     * @param string $dbHost
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     */
    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        parent::__construct(static::COMMAND_NAME);
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp(static::COMMAND_DESCRIPTION);

        $this->addArgument(static::ARGUMENT_NUMBER_OF_TICKS, InputArgument::OPTIONAL, static::ARGUMENT_NUMBER_OF_TICKS, 1);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->style = new SymfonyStyle($input, $output);

        $this->style->section('Drop and create database');
        $this->dropAndCreateDatabase();

        $this->style->section('Create scheme');
        $this->executeCommand('orm:schema-tool:create');

        $this->style->section('Run fixtures and create universe simulation');
        $this->executeCommand('app:doctrine:fixtures');

        $this->style->section('Run tick');
        for ($counter = 1; $counter <= $this->getNumberOfTicksArgument(); $counter++) {
            $this->style->note(sprintf('Tick %s', $counter));
            $this->executeCommand('app:tick:run', ['--force-tick', '--force-ranking']);
        }

        $this->style->section('Cache clear');
        $this->executeCommand('app:cache-clear');

        return 0;
    }

    /**
     * @return void
     */
    protected function dropAndCreateDatabase(): void
    {
        // connect
        $pdo = new PDO(sprintf('mysql:host=%s;dbname=%s;', $this->dbHost, $this->dbName), $this->dbUser, $this->dbPassword);

        $pdo->exec(sprintf('DROP DATABASE IF EXISTS %s', $this->dbName));
        $pdo->exec(sprintf('CREATE DATABASE %s', $this->dbName));

        // close
        $pdo = null;
    }

    /**
     * @param string $commandName
     * @param string[] $arguments
     *
     * @return void
     */
    protected function executeCommand(string $commandName, array $arguments = []): void
    {
        $command = $this->getApplication()->find($commandName);
        $command->run(new ArrayInput($arguments), $this->output);
    }

    /**
     * @return int
     */
    protected function getNumberOfTicksArgument(): int
    {
        return (int) $this->input->getArgument(static::ARGUMENT_NUMBER_OF_TICKS);
    }
}
