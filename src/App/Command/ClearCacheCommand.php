<?php

declare(strict_types=1);

namespace GC\App\Command;

use Inferno\Filesystem\Native\Directory;
use Inferno\Filesystem\Native\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ClearCacheCommand extends Command
{
    /**
     * @var \Inferno\Filesystem\Native\Directory[]
     */
    protected $cacheDirectories;

    /**
     * @var \Inferno\Filesystem\Native\File[]
     */
    protected $cacheFiles;

    /**
     * @var \Psr\SimpleCache\CacheInterface[]
     */
    protected $caches;

    /**
     * @var \Symfony\Component\Console\Style\OutputStyle
     */
    protected $style;

    /**
     * @var bool
     */
    protected $errors = false;

    /**
     * @param \Inferno\Filesystem\Native\Directory[] $cacheDirectories
     * @param \Inferno\Filesystem\Native\File[] $cacheFiles
     * @param \Psr\SimpleCache\CacheInterface[] $caches
     */
    public function __construct(array $cacheDirectories = [], array $cacheFiles = [], array $caches = [])
    {
        parent::__construct('app:cache-clear');
        $this->cacheDirectories = $cacheDirectories;
        $this->cacheFiles = $cacheFiles;
        $this->caches = $caches;
    }

    /**
     * @param \Inferno\Filesystem\Native\Directory $cacheDirectory
     *
     * @return void
     */
    public function addCacheDirectory(Directory $cacheDirectory): void
    {
        $this->cacheDirectories[] = $cacheDirectory;
    }

    /**
     * @param \Inferno\Filesystem\Native\File $cacheFile
     *
     * @return void
     */
    public function addCacheFile(File $cacheFile): void
    {
        $this->cacheFiles[] = $cacheFile;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('app:cache-clear');
        $this->setDescription('Clears the cache.');
        $this->setHelp('This command allows you to clear all caches (Filesystem/Persistence/PSR-Cache).');
    }

    /**
     * @return bool
     */
    protected function hasErrors(): bool
    {
        return $this->errors === true;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);
        $this->clearDirectories();
        $this->clearFiles();
        $this->clearCache();

        if ($this->style->getVerbosity() < OutputInterface::VERBOSITY_VERBOSE && $this->hasErrors()) {
            $this->style->error('Run "console app:cache-clear -v" for more info.');
        }

        return $this->hasErrors()? 1 : 0;
    }

    /**
     * @return void
     */
    protected function clearFiles(): void
    {
        $this->outputSection('Files');
        foreach ($this->cacheFiles as $cacheFile) {
            try {
                $cacheFile->write('');
                $this->outputSuccess((string) $cacheFile->getRealPath());
            } catch (\Throwable $throwable) {
                $this->errors = true;
                $this->outputError($throwable->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    protected function clearDirectories(): void
    {
        $this->outputSection('Directories');
        foreach ($this->cacheDirectories as $cacheDirectory) {
            try {
                $cacheDirectory->delete(true);
                $this->outputSuccess((string) $cacheDirectory->getRealPath());
            } catch (\Throwable $throwable) {
                $this->errors = true;
                $this->outputError($throwable->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    protected function clearCache(): void
    {
        $this->outputSection('PSR Cache');
        foreach ($this->caches as $cache) {
            try {
                $result = $cache->clear();

                if ($result === false) {
                    throw new \Exception(\get_class($cache) . ' failed');
                }

                $this->outputSuccess(\get_class($cache) . ' cleared');
            } catch (\Throwable $throwable) {
                $this->errors = true;
                $this->outputError($throwable->getMessage());
            }
        }
    }

    /**
     * @param string $output
     */
    protected function outputSection(string $output): void
    {
        if ($this->style->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->style->section($output);
        }
    }

    /**
     * @param string $output
     */
    protected function outputSuccess(string $output): void
    {
        if ($this->style->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->style->success($output);
        }
    }

    /**
     * @param string $output
     */
    protected function outputError(string $output): void
    {
        if ($this->style->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->style->error($output);
        }
    }
}
