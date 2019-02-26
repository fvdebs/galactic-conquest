<?php

declare(strict_types=1);

namespace GC\App;

use GC\App\Command\ClearCacheCommand;
use GC\App\Command\CreateHandlerCommand;
use GC\App\Http\ErrorResponseFactory;
use GC\Player\Model\PlayerRepository;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\UserRepository;
use GC\App\Middleware\GameMiddleware;
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
        $this->provideErrorResponseFactory($pimple);
        $this->provideGameMiddleware($pimple);

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

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    private function provideGameMiddleware(Container $container): void
    {
        $container->offsetSet(GameMiddleware::class, function(Container $container) {
            return new GameMiddleware(
                $container->offsetGet(SessionManagerInterface::class),
                $container->offsetGet(UserRepository::class),
                $container->offsetGet(UniverseRepository::class),
                $container->offsetGet(PlayerRepository::class),
                $container->offsetGet(\Twig_Environment::class),
                $container->offsetGet('uri-factory'),
                $container->offsetGet('response-factory'),
                $container->offsetGet(RouterChain::class),
                $container
            );
        });
    }
}
