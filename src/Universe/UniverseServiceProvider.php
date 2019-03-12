<?php

declare(strict_types=1);

namespace GC\Universe;

use Doctrine\ORM\EntityManager;
use GC\App\Middleware\AuthorizationUniverseMiddleware;
use GC\Universe\Command\TickCommand;
use GC\Universe\Handler\UniverseDetailsHandler;
use GC\Universe\Handler\UniverseRankingHandler;
use GC\Universe\Handler\UniverseRegisterHandler;
use GC\Universe\Handler\UniverseRegisterSaveHandler;
use GC\Universe\Handler\UniverseSelectHandler;
use GC\Universe\Model\Universe;
use GC\Universe\Model\UniverseRepository;
use Inferno\Routing\Route\RouteCollection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;

final class UniverseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideUniverseRoutes($pimple);
        $this->provideUniverseRepository($pimple);

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideTickCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUniverseRoutes(Container $container): void
    {
        $container->extend(RouteCollection::class, function(RouteCollection $collection, Container $container) {
            $collection->get('/{locale}/{universe}/ranking', UniverseRankingHandler::class);
            $collection->get('/{locale}/universes', UniverseSelectHandler::class);
            $collection->get('/{locale}/{universe}', UniverseDetailsHandler::class)
                ->addAttribute(AuthorizationUniverseMiddleware::SKIP_UNIVERSE_AUTH, true);

            $collection->get('/{locale}/{universe}/register', UniverseRegisterHandler::class)
                ->addAttribute(AuthorizationUniverseMiddleware::SKIP_UNIVERSE_AUTH, true);

            $collection->post('/{locale}/{universe}/register', UniverseRegisterSaveHandler::class)
                ->addAttribute(AuthorizationUniverseMiddleware::SKIP_UNIVERSE_AUTH, true);

            return $collection;
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideUniverseRepository(Container $container): void
    {
        $container->offsetSet(UniverseRepository::class, function(Container $container) {
            return $container->offsetGet(EntityManager::class)->getRepository(Universe::class);
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
            $application->add(new TickCommand(
                $container->offsetGet(UniverseRepository::class),
                $container->offsetGet(EntityManager::class),
                $container->offsetGet(LoggerInterface::class)
            ));

            return $application;
        });
    }
}