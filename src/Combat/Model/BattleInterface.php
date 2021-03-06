<?php

declare(strict_types=1);

namespace GC\Combat\Model;

interface BattleInterface
{
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
     * @param \GC\Combat\Model\FleetInterface $fleet
     */
    public function addAttackingFleet(FleetInterface $fleet): void;

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     */
    public function addDefendingFleet(FleetInterface $fleet): void;

    /**
     * @return int
     */
    public function getTargetExtractorsMetal(): int;

    /**
     * @return int
     */
    public function getTargetExtractorsCrystal(): int;

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseTargetExtractorsMetal(int $number): void;

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseTargetExtractorsCrystal(int $number): void;

    /**
     * @param int $fleetId
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return bool
     */
    public function hasFleetById(int $fleetId, array $fleets): bool;

    /**
     * @param int $fleetId
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface
     */
    public function getFleetById(int $fleetId, array $fleets): FleetInterface;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return float[]
     */
    public function getUnitSumFromFleets(array $fleets): array;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return float[]
     */
    public function getUnitLossesSumFromFleets(array $fleets): array;

    /**
     * @return void
     */
    public function __clone();
}
