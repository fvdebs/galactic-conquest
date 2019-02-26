<?php

declare(strict_types=1);

namespace GC\Progress;

use Doctrine\ORM\EntityManager;
use GC\Progress\Command\TickCommand;
use GC\Progress\Model\ProgressRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

final class ProgressServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideProgressRepository($pimple);

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideTickCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideProgressRepository(Container $container): void
    {
        $container->offsetSet(ProgressRepository::class, function(Container $container) {
            return new ProgressRepository($container->offsetGet(EntityManager::class));
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideTickCommand(Container $container): void
    {
        $container->extend(Application::class, function(Application $application, Container $container) {
            $application->add(new TickCommand());

            return $application;
        });
    }
}