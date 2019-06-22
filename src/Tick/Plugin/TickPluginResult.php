<?php

declare(strict_types=1);

namespace GC\Tick\Plugin;

use Throwable;

use function array_pop;
use function explode;

final class TickPluginResult implements TickPluginResultInterface
{
    /**
     * @var string
     */
    private $pluginClass;

    /**
     * @var float
     */
    private $time;

    /**
     * @var int
     */
    private $affectedRows;

    /**
     * @var bool
     */
    private $successful = true;

    /**
     * @var \Throwable|null
     */
    private $exception;

    /**
     * @var string[]
     */
    private $data = [];

    /**
     * @param int $affectedRows - default: 0
     */
    public function __construct(int $affectedRows = 0)
    {
        $this->affectedRows = $affectedRows;
    }

    /**
     * @return string
     */
    public function getPluginName(): string
    {
        if ($this->pluginClass === '' || $this->pluginClass === null) {
            return 'unknown';
        }

        $exploded = explode('\\', $this->pluginClass);

        return array_pop($exploded);
    }

    /**
     * @param string $pluginClass
     */
    public function setPluginClass(string $pluginClass): void
    {
        $this->pluginClass = $pluginClass;
    }

    /**
     * @return string
     */
    public function getPluginClass(): string
    {
        return $this->pluginClass;
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->time;
    }

    /**
     * @param float $time
     *
     * @return void
     */
    public function setTime(float $time): void
    {
        $this->time = $time;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function addData(string $data): void
    {
        $this->data[] = $data;
    }

    /**
     * @return string
     */
    public function getDataAsString(): string
    {
        return implode("\n", $this->data);
    }

    /**
     * @return int
     */
    public function getAffectedRows(): int
    {
        return $this->affectedRows;
    }

    /**
     * @param int $affected
     *
     * @return void
     */
    public function setAffectedRows(int $affected): void
    {
        $this->affectedRows = $affected;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * @param bool $successful
     */
    public function setSuccessful(bool $successful): void
    {
        $this->successful = $successful;
    }

    /**
     * @return \Throwable|null
     */
    public function getException(): ?Throwable
    {
        return $this->exception;
    }

    /**
     * @return bool
     */
    public function hasException(): bool
    {
        return $this->exception !== null;
    }

    /**
     * @param \Throwable $exception
     *
     * @return void
     */
    public function setException(Throwable $exception): void
    {
        $this->exception = $exception;
    }
}
