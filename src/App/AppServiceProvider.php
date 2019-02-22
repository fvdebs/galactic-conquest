<?php

declare(strict_types=1);

namespace GC\App;

use GC\App\Command\ClearCacheCommand;
use GC\App\Http\ErrorResponseFactory;
use Inferno\Filesystem\Native\Directory;
use Inferno\Filesystem\Native\File;
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

        if ($pimple->offsetGet('config.isCli')) {
            $this->provideClearCacheCommand($pimple);
        }
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function provideErrorResponseFactory(Container $container): void
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
    protected function provideClearCacheCommand(Container $container): void
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
}
