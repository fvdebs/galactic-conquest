<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface BattleInterface
{
    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets();

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets();

    /**
     * @return int
     */
    public function getTargetExtractorsMetal(): int;

    /**
     * @return int
     */
    public function getTargetExtractorsCrystal(): int;

    /**
     * @return string[]
     */
    public function getTargetData(): array;

    /**
     * @param string $dataKey
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function groupFleetsByDataKey(string $dataKey, array $fleets): array;
}
