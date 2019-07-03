<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

interface CombatSimulationRequestInterface
{
    /**
     * @return int
     */
    public function getTicksToSimulate(): int;

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param int $incomingAtTick
     * @param int $durationTicks
     *
     * @return void
     */
    public function addAttackingFleet(FleetInterface $fleet, int $incomingAtTick, int $durationTicks): void;

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param int $incomingAtTick
     * @param int $durationTicks
     *
     * @return void
     */
    public function addDefendingFleet(FleetInterface $fleet, int $incomingAtTick, int $durationTicks): void;

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets(): array;

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets(): array;

    /**
     * @param int $fleetId
     * @param int $tick
     *
     * @return bool
     */
    public function isFleetInTick(int $fleetId, int $tick): bool;

    /**
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function getSettings(): SettingsInterface;

    /**
     * @return int
     */
    public function getTargetExtractorsMetal(): int;

    /**
     * @return int
     */
    public function getTargetExtractorsCrystal(): int;

    /**
     * @param int $targetExtractorsMetal
     */
    public function setTargetExtractorsMetal(int $targetExtractorsMetal): void;

    /**
     * @param int $targetExtractorsCrystal
     */
    public function setTargetExtractorsCrystal(int $targetExtractorsCrystal): void;

    /**
     * @return string[]
     */
    public function getData(): array;
}
