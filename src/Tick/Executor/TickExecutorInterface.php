<?php

declare(strict_types=1);

namespace GC\Tick\Executor;

interface TickExecutorInterface
{
    /**
     * @param int $universeId
     * @param bool $force - default: false
     *
     * @return bool
     */
    public function isCalculationNeeded(int $universeId, bool $force = false): bool;

    /**
     * @param int $universeId
     *
     * @return \GC\Tick\Executor\TickExecutorResultInterface
     */
    public function calculate(int $universeId): TickExecutorResultInterface;

    /**
     * @return \GC\Universe\Model\Universe[]
     *
     * @return void
     */
    public function findUniversesWhichNeedsCalculation(): array;
}
