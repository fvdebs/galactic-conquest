<?php

declare(strict_types=1);

namespace GC\Combat;

use GC\App\Middleware\AuthorizationUniverseMiddleware;
use GC\Combat\Calculator\Calculator;
use GC\Combat\Calculator\CalculatorInterface;
use GC\Combat\Calculator\Plugin\CarrierCalculatorPlugin;
use GC\Combat\Calculator\Plugin\CombatCalculatorPlugin;
use GC\Combat\Calculator\Plugin\ExtractorCalculatorPlugin;
use GC\Combat\Calculator\Plugin\SalvageCalculatorPlugin;
use GC\Combat\Format\JsonFormatter;
use GC\Combat\Format\JsonFormatterInterface;
use GC\Combat\Handler\CombatSimulatorHandler;
use GC\Combat\Mapper\SettingsMapper;
use GC\Combat\Mapper\SettingsMapperInterface;
use GC\Combat\Report\CombatReportGenerator;
use GC\Combat\Report\CombatReportGeneratorInterface;
use GC\Combat\Service\CombatService;
use GC\Combat\Service\CombatServiceInterface;
use GC\Combat\Command\CombatTestCommand;
use Inferno\Routing\Route\RouteCollection;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;
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
        $this->providePlayerRoutes($pimple);
        $this->provideCalculator($pimple);
        $this->provideJsonFormatter($pimple);
        $this->provideCombatReportGenerator($pimple);
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
    private function providePlayerRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function (RouteCollection $collection, Container $container)
        {
            $collection->get('/{locale}/{universe}/simulator', function(PsrContainer $container) {
                return new CombatSimulatorHandler(
                    $container->get('renderer'),
                    $container->get('response-factory'),
                    $container->get(CombatServiceInterface::class)
                );
            })
                ->addAttribute('public', true)
                ->addAttribute(AuthorizationUniverseMiddleware::SKIP_UNIVERSE_AUTH, true);

            return $collection;
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatService(Container $container): void
    {
        $container->offsetSet(CombatServiceInterface::class, static function (Container $container) {
            return new CombatService(
                $container->offsetGet(CalculatorInterface::class),
                $container->offsetGet(JsonFormatterInterface::class),
                $container->offsetGet(CombatReportGeneratorInterface::class),
                $container->offsetGet(SettingsMapperInterface::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCombatReportGenerator(Container $container): void
    {
        $container->offsetSet(CombatReportGeneratorInterface::class, static function (Container $container) {
            return new CombatReportGenerator(
                $container->offsetGet('renderer')
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
            new SalvageCalculatorPlugin(),
            new CarrierCalculatorPlugin(),
            new ExtractorCalculatorPlugin(),
        ];
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideMapper(Container $container): void
    {
        $container->offsetSet(SettingsMapperInterface::class, static function () {
            return new SettingsMapper();
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
