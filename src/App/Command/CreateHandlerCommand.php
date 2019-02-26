<?php

declare(strict_types=1);

namespace GC\App\Command;

use Inferno\Renderer\RendererInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateHandlerCommand extends Command
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var \Inferno\Renderer\RendererInterface
     */
    private $renderer;

    /**
     * @param string $baseDir
     * @param \Inferno\Renderer\RendererInterface $renderer
     */
    public function __construct(string $baseDir, RendererInterface $renderer)
    {
        parent::__construct('app:create:handler');
        $this->baseDir = $baseDir;
        $this->renderer = $renderer;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:create:handler');
        $this->setDescription('Creates a command.');
        $this->setHelp('This command allows you create a command.');

        $this->addArgument('moduleName', InputArgument::REQUIRED);
        $this->addArgument('className', InputArgument::REQUIRED);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleName = ucfirst(trim($input->getArgument('moduleName')));
        $className = ucfirst(trim($input->getArgument('className')));
        $templateName = lcfirst($className);
        $routeName = strtolower(str_replace(' ', '.', preg_replace('/(\w+)([A-Z])/U', '\\1 \\2', $className)));

        // create handler
        $handlerFilepath = sprintf(
            '%s/src/%s/Handler/%sHandler.php',
            $this->baseDir,
            $moduleName,
            $className
        );

        file_put_contents($handlerFilepath, $this->renderer->render('@App/generator/handler.twig', [
            'moduleName' => $moduleName,
            'className' => $className,
            'templateName' => $templateName,
            'routeName' => $routeName,
        ]));

        // create template
        $templateFilepath = sprintf(
            '%s/src/%s/Templates/%s.twig',
            $this->baseDir,
            $moduleName,
            $templateName
        );

        file_put_contents(
            $templateFilepath,
            $this->renderer->render('@App/generator/template.twig')
        );

        $printRouteCommand = sprintf(
            '$collection->get(\'/{locale}/{universe}/%s\', %sHandler::class);',
            \strtolower($moduleName),
            $className
        );

        $output->writeln($printRouteCommand);

        return 0;
    }
}