<?php

declare(strict_types=1);

namespace GC\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TickCommand extends Command
{
    /**
     * TickCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('app:tick');
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:tick');
        $this->setDescription('Calculates a tick.');
        $this->setHelp('This command starts a tick.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 0;
    }
}