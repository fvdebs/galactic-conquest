<?php

declare(strict_types=1);

namespace GC\Tick;

use Doctrine\ORM\EntityManager;
use GC\Tick\Executor\TickExecutor;
use GC\Tick\Executor\TickExecutorInterface;
use GC\Tick\Command\TickCommand;
use GC\Tick\Model\TickRepository;
use GC\Tick\Plugin\AllianceCalculateAverageGalaxyPointsPlugin;
use GC\Tick\Plugin\AllianceFinishTechnologyConstructionsPlugin;
use GC\Tick\Plugin\AllianceIncreaseExtractorPointsPlugin;
use GC\Tick\Plugin\AllianceIncreaseResourceIncomePlugin;
use GC\Tick\Plugin\GalaxyCalculateAveragePlayerPointsPlugin;
use GC\Tick\Plugin\GalaxyFinishTechnologyConstructionsPlugin;
use GC\Tick\Plugin\GalaxyIncreaseExtractorPointsPlugin;
use GC\Tick\Plugin\GalaxyIncreaseResourceIncomePlugin;
use GC\Tick\Plugin\PlayerCalculatePointsPlugin;
use GC\Tick\Plugin\PlayerClearFleetsPlugin;
use GC\Tick\Plugin\PlayerCombatPlugin;
use GC\Tick\Plugin\PlayerFinishTechnologyConstructionsPlugin;
use GC\Tick\Plugin\PlayerFinishUnitConstructionsPlugin;
use GC\Tick\Plugin\PlayerIncreaseResourceIncomePlugin;
use GC\Tick\Plugin\PlayerMoveFleetsPlugin;
use GC\Tick\Plugin\UniverseIncreaseTickPlugin;
use GC\Universe\Model\UniverseRepository;
use GC\Tick\Plugin\TickPluginInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

final class TickServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideTickRepository($pimple);
        $this->provideTickExecutor($pimple);

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideTickCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideTickCommand(Container $container): void
    {
        $container->extend(Application::class, function (Application $application, Container $container) {
            $application->add(new TickCommand(
                $container->offsetGet(TickExecutorInterface::class),
                $container->offsetGet('baseDir')
            ));

            return $application;
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideTickRepository(Container $container): void
    {
        $container->offsetSet(TickRepository::class, function (Container $container) {
            return new TickRepository(
                $container->offsetGet(EntityManager::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideTickExecutor(Container $container): void
    {
        $container->offsetSet(TickExecutorInterface::class, function (Container $container) {
            return new TickExecutor(
                $this->getTickPlugins($container),
                $container->offsetGet(EntityManager::class),
                $container->offsetGet(UniverseRepository::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface[]
     */
    private function getTickPlugins(Container $container): array
    {
        return [
            $this->createUniverseIncreaseTickPlugin($container),
            $this->createPlayerFinishTechnologyConstructionsPlugin($container),
            $this->createPlayerFinishUnitConstructionsPlugin($container),
            $this->createPlayerIncreaseResourceIncomePlugin($container),
            $this->createPlayerMoveFleetsPlugin($container),
            $this->createPlayerCombatPlugin($container),
            $this->createPlayerClearFleetsPlugin($container),
            $this->createPlayerCalculatePointsPlugin($container),
            $this->createGalaxyFinishTechnologyConstructionsPlugin($container),
            $this->createGalaxyIncreaseExtractorPointsPlugin($container),
            $this->createGalaxyIncreaseResourceIncomePlugin($container),
            $this->createGalaxyCalculateAveragePlayerPointsPlugin($container),
            $this->createAllianceFinishTechnologyConstructionsPlugin($container),
            $this->createAllianceIncreaseExtractorPointsPlugin($container),
            $this->createAllianceIncreaseResourceIncomePlugin($container),
            $this->createAllianceCalculateAverageGalaxyPointsPlugin($container),
        ];
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createUniverseIncreaseTickPlugin(Container $container): TickPluginInterface
    {
        return new UniverseIncreaseTickPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerIncreaseResourceIncomePlugin(Container $container): TickPluginInterface
    {
        return new PlayerIncreaseResourceIncomePlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerFinishTechnologyConstructionsPlugin(Container $container): TickPluginInterface
    {
        return new PlayerFinishTechnologyConstructionsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerFinishUnitConstructionsPlugin(Container $container): TickPluginInterface
    {
        return new PlayerFinishUnitConstructionsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerMoveFleetsPlugin(Container $container): TickPluginInterface
    {
        return new PlayerMoveFleetsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerClearFleetsPlugin(Container $container): TickPluginInterface
    {
        return new PlayerClearFleetsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerCombatPlugin(Container $container): TickPluginInterface
    {
        return new PlayerCombatPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createPlayerCalculatePointsPlugin(Container $container): TickPluginInterface
    {
        return new PlayerCalculatePointsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createGalaxyFinishTechnologyConstructionsPlugin(Container $container): TickPluginInterface
    {
        return new GalaxyFinishTechnologyConstructionsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createGalaxyIncreaseExtractorPointsPlugin(Container $container): TickPluginInterface
    {
        return new GalaxyIncreaseExtractorPointsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createGalaxyIncreaseResourceIncomePlugin(Container $container): TickPluginInterface
    {
        return new GalaxyIncreaseResourceIncomePlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createGalaxyCalculateAveragePlayerPointsPlugin(Container $container): TickPluginInterface
    {
        return new GalaxyCalculateAveragePlayerPointsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createAllianceFinishTechnologyConstructionsPlugin(Container $container): TickPluginInterface
    {
        return new AllianceFinishTechnologyConstructionsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createAllianceIncreaseExtractorPointsPlugin(Container $container): TickPluginInterface
    {
        return new AllianceIncreaseExtractorPointsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createAllianceIncreaseResourceIncomePlugin(Container $container): TickPluginInterface
    {
        return new AllianceIncreaseResourceIncomePlugin(
            $container->offsetGet(TickRepository::class)
        );
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return \GC\Tick\Plugin\TickPluginInterface
     */
    private function createAllianceCalculateAverageGalaxyPointsPlugin(Container $container): TickPluginInterface
    {
        return new AllianceCalculateAverageGalaxyPointsPlugin(
            $container->offsetGet(TickRepository::class)
        );
    }
}
