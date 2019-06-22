<?php

declare(strict_types=1);

namespace GC\Tick\Plugin;

use Throwable;

interface TickPluginResultInterface
{
    /**
     * @return string
     */
    public function getPluginName(): string;

    /**
     * @param string $pluginClass
     *
     * @return void
     */
    public function setPluginClass(string $pluginClass): void;

    /**
     * @return string
     */
    public function getPluginClass(): string;

    /**
     * @return float
     */
    public function getTime(): float;

    /**
     * @param float $time
     *
     * @return void
     */
    public function setTime(float $time): void;

    /**
     * @return string[]
     */
    public function getData(): array;

    /**
     * @param string $data
     *
     * @return void
     */
    public function addData(string $data): void;

    /**
     * @return string
     */
    public function getDataAsString(): string;

    /**
     * @return int
     */
    public function getAffectedRows(): int;

    /**
     * @param int $affected
     *
     * @return void
     */
    public function setAffectedRows(int $affected): void;

    /**
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * @param bool $successful
     */
    public function setSuccessful(bool $successful): void;

    /**
     * @return \Throwable|null
     */
    public function getException(): ?Throwable;

    /**
     * @return bool
     */
    public function hasException(): bool;

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    public function setException(Throwable $exception): void;
}
