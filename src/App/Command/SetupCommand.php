<?php

declare(strict_types=1);

namespace GC\App\Command;

use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
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

        $this->style->success('Drop database if exists');
        $this->style->success('Create database');
        $this->dropAndCreateDatabase();

        $this->style->success('Create database scheme');
        $this->executeCommand('orm:schema-tool:create', [], true);

        $this->style->success('Create doctrine proxies');
        $this->executeCommand('orm:generate-proxies', [], true);

        $this->style->success('Clearing cache', [], true);
        $this->executeCommand('app:cache-clear', [], true);
        $this->executeCommand('orm:clear-cache:metadata', [], true);
        $this->executeCommand('orm:clear-cache:query', [], true);
        $this->executeCommand('orm:clear-cache:result', [], true);

        $this->style->success('Create universe simulation');
        $this->executeCommand('app:doctrine:fixtures');

        $this->style->success('Running Ticks');
        for ($counter = 1; $counter <= $this->getNumberOfTicksArgument(); $counter++) {
            $this->style->newLine();
            $this->style->title(sprintf('Tick %s', $counter));
            $this->executeCommand('app:tick:run', ['--force' => true]);
        }

        $this->style->newLine();
        $this->style->success('Setup Completed');

        return 0;
    }

    /**
     * @return void
     */
    protected function dropAndCreateDatabase(): void
    {
        // connect
        // dbname=%s;
        $pdo = new PDO(sprintf('mysql:host=%s;', $this->dbHost), $this->dbUser, $this->dbPassword);

        $pdo->exec(sprintf('DROP DATABASE IF EXISTS %s', $this->dbName));
        $pdo->exec(sprintf('CREATE DATABASE %s', $this->dbName));

        // close
        $pdo = null;
    }

    /**
     * @param string $commandName
     * @param string[] $arguments
     * @param bool $verbose
     *
     * @return void
     */
    protected function executeCommand(string $commandName, array $arguments = [], bool $verbose = false): void
    {
        $command = $this->getApplication()
            ->find($commandName);

        $command->run(
            new ArrayInput($arguments),
            $verbose ? new NullOutput() : $this->output
        );
    }

    /**
     * @return int
     */
    protected function getNumberOfTicksArgument(): int
    {
        return (int) $this->input->getArgument(static::ARGUMENT_NUMBER_OF_TICKS);
    }
}
