<?php

declare(strict_types=1);

namespace GC\Combat;

use GC\Combat\Calculator\Calculator;
use GC\Combat\Calculator\CalculatorInterface;
use GC\Combat\Calculator\Plugin\CarrierCalculatorPlugin;
use GC\Combat\Calculator\Plugin\CarrierConsumptionCalculatorPlugin;
use GC\Combat\Calculator\Plugin\CarrierLossesCalculatorPlugin;
use GC\Combat\Calculator\Plugin\CombatCalculatorPlugin;
use GC\Combat\Calculator\Plugin\ExtractorDistributionCalculatorPlugin;
use GC\Combat\Calculator\Plugin\ExtractorGuardedCalculatorPlugin;
use GC\Combat\Calculator\Plugin\ExtractorStolenCalculatorPlugin;
use GC\Combat\Calculator\Plugin\SalvageCalculatorPlugin;
use GC\Combat\Format\JsonFormatter;
use GC\Combat\Format\JsonFormatterInterface;
use GC\Combat\Mapper\Mapper;
use GC\Combat\Mapper\MapperInterface;
use GC\Combat\Service\CombatService;
use GC\Combat\Service\CombatServiceInterface;
use GC\Combat\Command\CombatTestCommand;
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
        $this->provideCalculator($pimple);
        $this->provideJsonFormatter($pimple);
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
        $container->offsetSet(CombatServiceInterface::class, function (Container $container) {
            return new CombatService(
                $container->offsetGet(CalculatorInterface::class),
                $container->offsetGet(JsonFormatterInterface::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideJsonFormatter(Container $container): void
    {
        $container->offsetSet(JsonFormatterInterface::class, static function () {
            return new JsonFormatter();
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCalculator(Container $container): void
    {
        $container->offsetSet(CalculatorInterface::class, function () {
            return new Calculator(
                $this->getCalculatorPlugins()
            );
        });
    }

    /**
     * @return \GC\Combat\Calculator\Plugin\CalculatorPluginInterface[]
     */
    private function getCalculatorPlugins(): array
    {
        return [
            new CombatCalculatorPlugin(),
            new CarrierCalculatorPlugin(),
            new SalvageCalculatorPlugin(),
            new ExtractorGuardedCalculatorPlugin(),
            new ExtractorStolenCalculatorPlugin(),
        ];
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
                $container->offsetGet(CombatServiceInterface::class)
            ));

            return $application;
        });
    }
}
