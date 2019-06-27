<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface BattleInterface
{
    /**
     * @return string[]
     */
    public function getData(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasDataValue(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getDataValue(string $key);

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets(): array;

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets(): array;

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
     * @param string $key
     *
     * @return bool
     */
    public function hasTargetDataValue(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getTargetDataValue(string $key);

    /**
     * @param int $fleetReference
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface
     */
    public function getFleetByReference(int $fleetReference, array $fleets): FleetInterface;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return float[]
     */
    public function getUnitSumFromFleets(array $fleets): array;

    /**
     * Returns true if its equal and not empty.
     *
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param string $dataKey
     *
     * @return bool
     */
    public function compareFleetDataValueWithTargetDataValue(FleetInterface $fleet, string $dataKey): bool;

    /**
     * @return void
     */
    public function __clone();
}
