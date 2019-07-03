<?php

declare(strict_types=1);

namespace GC\Combat\Service;

use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;

use function array_key_exists;

final class CombatSimulationRequest implements CombatSimulationRequestInterface
{
    /**
     * @var int
     */
    private $ticksToSimulate;

    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $attackingFleets = [];

    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $defendingFleets = [];

    /**
     * @var int[]
     */
    private $incomingTickFleets = [];

    /**
     * @var int[]
     */
    private $durationTickFleets = [];

    /**
     * @var SettingsInterface
     */
    private $settings;

    /**
     * @var int
     */
    private $targetExtractorsMetal = 0;

    /**
     * @var int
     */
    private $targetExtractorsCrystal = 0;

    /**
     * @var string[]
     */
    private $data;

    /**
     * @param int $ticksToSimulate
     * @param \GC\Combat\Model\SettingsInterface $settings
     * @param int $targetExtractorsMetal
     * @param int $targetExtractorsCrystal
     * @param string[] $data
     */
    public function __construct(int $ticksToSimulate, SettingsInterface $settings, int $targetExtractorsMetal, int $targetExtractorsCrystal, array $data)
    {
        $this->ticksToSimulate = $ticksToSimulate;
        $this->settings = $settings;
        $this->targetExtractorsMetal = $targetExtractorsMetal;
        $this->targetExtractorsCrystal = $targetExtractorsCrystal;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getTicksToSimulate(): int
    {
        return $this->ticksToSimulate;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param int $incomingAtTick
     * @param int $durationTicks
     *
     * @return void
     */
    public function addAttackingFleet(FleetInterface $fleet, int $incomingAtTick, int $durationTicks): void
    {
        $this->attackingFleets[] = $fleet;
        $this->incomingTickFleets[$fleet->getFleetId()] = $incomingAtTick;
        $this->durationTickFleets[$fleet->getFleetId()] = $durationTicks;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param int $incomingAtTick
     * @param int $durationTicks
     *
     * @return void
     */
    public function addDefendingFleet(FleetInterface $fleet, int $incomingAtTick, int $durationTicks): void
    {
        $this->defendingFleets[] = $fleet;
        $this->incomingTickFleets[$fleet->getFleetId()] = $incomingAtTick;
        $this->durationTickFleets[$fleet->getFleetId()] = $durationTicks;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets(): array
    {
        return $this->attackingFleets;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets(): array
    {
        return $this->defendingFleets;
    }

    /**
     * @param int $fleetId
     * @param int $tick
     *
     * @return bool
     */
    public function isFleetInTick(int $fleetId, int $tick): bool
    {
        if (!array_key_exists($fleetId, $this->incomingTickFleets)) {
            return false;
        }

        if (!array_key_exists($fleetId, $this->durationTickFleets)) {
            return false;
        }

        $incomingAt = $this->incomingTickFleets[$fleetId];
        $duration = $this->durationTickFleets[$fleetId];
        $outgoingTick = $incomingAt + $duration;

        if ($incomingAt <= $tick && $outgoingTick > $tick) {
            return true;
        }

        return false;
    }

    /**
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function getSettings(): SettingsInterface
    {
        return $this->settings;
    }

    /**
     * @return int
     */
    public function getTargetExtractorsMetal(): int
    {
        return $this->targetExtractorsMetal;
    }

    /**
     * @return int
     */
    public function getTargetExtractorsCrystal(): int
    {
        return $this->targetExtractorsCrystal;
    }

    /**
     * @param int $targetExtractorsMetal
     */
    public function setTargetExtractorsMetal(int $targetExtractorsMetal): void
    {
        $this->targetExtractorsMetal = $targetExtractorsMetal;
    }

    /**
     * @param int $targetExtractorsCrystal
     */
    public function setTargetExtractorsCrystal(int $targetExtractorsCrystal): void
    {
        $this->targetExtractorsCrystal = $targetExtractorsCrystal;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
