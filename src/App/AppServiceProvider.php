<?php

declare(strict_types=1);

namespace GC\App;

use GC\App\Command\ClearCacheCommand;
use GC\App\Command\CreateHandlerCommand;
use GC\App\Http\ErrorResponseFactory;
use GC\App\Middleware\AuthorizationMiddleware;
use GC\App\Middleware\SetCurrentPlayerMiddleware;
use GC\App\Middleware\SetCurrentUniverseMiddleware;
use GC\App\Middleware\SetCurrentUserMiddleware;
use GC\App\Middleware\SetTwigGlobalsMiddleware;
use GC\App\Middleware\AuthorizationUniverseMiddleware;
use GC\Player\Model\PlayerRepository;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\UserRepository;
use Inferno\Filesystem\Native\Directory;
use Inferno\Filesystem\Native\File;
use Inferno\Routing\Router\RouterChain;
use Inferno\Session\Manager\SessionManagerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

final class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideSetCurrentUserMiddleware($pimple);
        $this->provideSetCurrentUniverseMiddleware($pimple);
        $this->provideSetCurrentPlayerMiddleware($pimple);
        $this->provideSetTwigGlobalsMiddleware($pimple);
        $this->provideAuthorizationMiddleware($pimple);
        $this->provideAuthorizationUniverseMiddleware($pimple);

        $this->provideErrorResponseFactory($pimple);

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideClearCacheCommand($pimple);
            $this->provideCreateHandlerCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideSetCurrentUserMiddleware(Container $container): void
    {
        $container->offsetSet(SetCurrentUserMiddleware::class, function(Container $container) {
            return new SetCurrentUserMiddleware(
                $container->offsetGet(SessionManagerInterface::class),
                $container->offsetGet(UserRepository::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideSetCurrentUniverseMiddleware(Container $container): void
    {
        $container->offsetSet(SetCurrentUniverseMiddleware::class, function(Container $container) {
            return new SetCurrentUniverseMiddleware(
                $container->offsetGet(UniverseRepository::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideSetCurrentPlayerMiddleware(Container $container): void
    {
        $container->offsetSet(SetCurrentPlayerMiddleware::class, function(Container $container) {
            return new SetCurrentPlayerMiddleware(
                $container->offsetGet(PlayerRepository::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideSetTwigGlobalsMiddleware(Container $container): void
    {
        $container->offsetSet(SetTwigGlobalsMiddleware::class, function(Container $container) {
            return new SetTwigGlobalsMiddleware(
                $container->offsetGet(\Twig_Environment::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideAuthorizationMiddleware(Container $container): void
    {
        $container->offsetSet(AuthorizationMiddleware::class, function(Container $container) {
            return new AuthorizationMiddleware(
                $container->offsetGet('response-factory'),
                $container->offsetGet(RouterChain::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideAuthorizationUniverseMiddleware(Container $container): void
    {
        $container->offsetSet(AuthorizationUniverseMiddleware::class, function(Container $container) {
            return new AuthorizationUniverseMiddleware(
                $container->offsetGet('response-factory'),
                $container->offsetGet(RouterChain::class)
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideErrorResponseFactory(Container $container): void
    {
        $container->offsetSet('error-response-factory', function(Container $container) {
            $catchErrors = ! $container->offsetGet('config.isDev');

            return new ErrorResponseFactory(
                $catchErrors,
                $container->offsetGet('response-factory'),
                $container->offsetGet('renderer')
            );
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideClearCacheCommand(Container $container): void
    {
        $container->extend(Application::class, function(Application $application, Container $container) {
            $cacheDirectories = (array) $container->offsetGet('app.cache-dirs');
            $cacheFiles = (array) $container->offsetGet('app.cache-files');

            $command = new ClearCacheCommand();

            foreach($cacheDirectories as $directoryPath) {
                $command->addCacheDirectory(new Directory($directoryPath));
            }

            foreach($cacheFiles as $filePath) {
                $command->addCacheFile(new File($filePath));
            }

            $application->add($command);

            return $application;
        });
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideCreateHandlerCommand(Container $container): void
    {
        $container->extend(Application::class, function(Application $application, Container $container) {
            $application->add(new CreateHandlerCommand(
                $container->offsetGet('baseDir'),
                $container->offsetGet('renderer')
            ));

            return $application;
        });
    }
}
