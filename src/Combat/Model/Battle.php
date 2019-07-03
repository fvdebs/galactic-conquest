<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use GC\Combat\Exception\FleetNotFoundException;

use function array_key_exists;
use function is_object;
use function is_array;
use function unserialize;
use function serialize;

final class Battle implements BattleInterface
{
    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $attackingFleets;

    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $defendingFleets;

    /**
     * @var int
     */
    private $targetExtractorsMetal;

    /**
     * @var int
     */
    private $targetExtractorsCrystal;

    /**
     * @var string[]
     */
    private $data;

    /**
     * @var string[]
     */
    private $targetData;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $attackingFleets
     * @param \GC\Combat\Model\FleetInterface[] $defendingFleets - default: []
     * @param int $targetExtractorsMetal - default: 0
     * @param int $targetExtractorsCrystal - default: 0
     * @param string[] $data - default: []
     * @param array $targetData - default: []
     */
    public function __construct(
        array $attackingFleets,
        array $defendingFleets = [],
        int $targetExtractorsMetal = 0,
        int $targetExtractorsCrystal = 0,
        array $data = [],
        array $targetData = []
    ) {
        $this->attackingFleets = $attackingFleets;
        $this->defendingFleets = $defendingFleets;
        $this->targetExtractorsMetal = $targetExtractorsMetal;
        $this->targetExtractorsCrystal = $targetExtractorsCrystal;
        $this->data = $data;
        $this->targetData = $targetData;
    }

    /**
     * @return string[]
     */
    public function getTargetData(): array
    {
        return $this->targetData;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasTargetDataValue(string $key): bool
    {
        return array_key_exists($key, $this->targetData);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getTargetDataValue(string $key)
    {
        if ($this->hasTargetDataValue($key)) {
            return $this->targetData[$key];
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasDataValue(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getDataValue(string $key)
    {
        if ($this->hasDataValue($key)) {
            return $this->data[$key];
        }

        return null;
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
     * @param \GC\Combat\Model\FleetInterface $fleet
     */
    public function addAttackingFleet(FleetInterface $fleet): void
    {
        $this->attackingFleets[] = $fleet;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface $fleet
     */
    public function addDefendingFleet(FleetInterface $fleet): void
    {
        $this->defendingFleets[] = $fleet;
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
     * @param int $number
     *
     * @return void
     */
    public function decreaseTargetExtractorsMetal(int $number): void
    {
        $this->targetExtractorsMetal -= $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseTargetExtractorsCrystal(int $number): void
    {
        $this->targetExtractorsCrystal -= $number;
    }

    /**
     * @param int $fleetId
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return bool
     */
    public function hasFleetById(int $fleetId, array $fleets): bool
    {
        foreach ($fleets as $fleet) {
            if ($fleet->getFleetId() === $fleetId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $fleetId
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface
     */
    public function getFleetById(int $fleetId, array $fleets): FleetInterface
    {
        foreach ($fleets as $fleet) {
            if ($fleet->getFleetId() === $fleetId) {
                return $fleet;
            }
        }

        throw FleetNotFoundException::fromFleetReference($fleetId);
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return float[]
     */
    public function getUnitSumFromFleets(array $fleets): array
    {
        $summary = [];

        foreach ($fleets as $fleet) {
            foreach ($fleet->getUnits() as $unitId => $quantity) {
                if ($quantity === 0.0) {
                    continue;
                }

                if (!array_key_exists($unitId, $summary)) {
                    $summary[$unitId] = 0.0;
                }

                $summary[$unitId] += $quantity;
            }
        }

        return $summary;
    }

    /**
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return float[]
     */
    public function getUnitLossesSumFromFleets(array $fleets): array
    {
        $summary = [];

        foreach ($fleets as $fleet) {
            foreach ($fleet->getUnitsLost() as $unitId => $quantity) {
                if ($quantity === 0.0) {
                    continue;
                }

                if (!array_key_exists($unitId, $summary)) {
                    $summary[$unitId] = 0.0;
                }

                $summary[$unitId] += $quantity;
            }
        }

        return $summary;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach($this as $key => $val) {
            if (is_object($val) || is_array($val)) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
