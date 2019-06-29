<?php

declare(strict_types=1);

namespace GC\Combat\Report;

interface CombatReportGeneratorInterface
{
    /**
     * @param string $json
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string $pointOfViewDisplayNameDataKey
     *
     * @return \GC\Combat\Report\CombatReportGeneratorResponseInterface
     */
    public function generateCombatReportFromJson(
        string $json,
        $pointOfView,
        string $pointOfViewDataKey,
        string $pointOfViewDisplayNameDataKey
    ): CombatReportGeneratorResponseInterface;
}
