<?php

declare(strict_types=1);

namespace GC\Combat;

use GC\Combat\Calculator\Calculator;
use GC\Combat\Format\JsonFormatter;
use GC\Combat\Mapper\Mapper;
use GC\Combat\Mapper\MapperInterface;
use GC\Combat\Service\CombatService;
use GC\Combat\Service\CombatServiceInterface;
use GC\Combat\Command\CombatTestCommand;
use GC\Universe\Model\UniverseRepository;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

final class CombatServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideCombatService($pimple);
        $this->provideMapper($pimple);

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideCombatTestCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatService(Container $container): void
    {
        $container->offsetSet(CombatServiceInterface::class, static function () {
            return new CombatService(
                new Calculator(),
                new JsonFormatter()
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideMapper(Container $container): void
    {
        $container->offsetSet(MapperInterface::class, static function () {
            return new Mapper();
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatTestCommand(Container $container): void
    {
        $container->extend(Application::class, function (Application $application, Container $container) {
            $application->add(new CombatTestCommand(
                $container->offsetGet(CombatServiceInterface::class),
                $container->offsetGet(UniverseRepository::class),
                $container->offsetGet(MapperInterface::class)
            ));

            return $application;
        });
    }
}
